<?php

spl_autoload_register(function($class) {

    $replace = str_replace('\\', '/', $class);
    $explode = explode('/', $replace, 4);
    end($explode);
    $no_root = current($explode);

    $file = sprintf('%s/%s.php', __DIR__, $no_root);

    if (is_file($file)) {
        require_once $file;
    }
});

interface AdapterInterface {

    public function connect();

    public function disconnect();

    public function getDbName();

    public function getAllObjects();

    public function getStatement();

    public function getPDOInstance();
    
    /**
     * 
     * @param type $structure
     * @return \smn\lazyc\dbc\Catalog\SchemaInterface[]
     */
    public function makeStructure($structure);
}
/**
 * 
 * [
 *
 * hostname =>
 * port =>
 * sock => 
 * username =>
 * password =>
 * driver =>
 *  
 * ]
 * 
 * 
 */
$info = [
    'hostname' => '192.168.56.101',
    'port' => 3306,
    'username' => 'root',
    'password' => '',
    'database' => 'of',
    'driver' => 'pdo_mysql'
];

class DBC {

    protected static $adapter_class = [
        'pdo_mysql' => MysqlAdapter::class
    ];

    /**
     * 
     * @param type $config
     * @param type $options
     * @return AdapterInterface
     * @throws Exception
     * @throws \Exception
     */
    public static function getAdapter($config, $options) {
        if (!array_key_exists('driver', $config)) {
            throw new Exception('Driver non specificato');
            return; // exception
        }
        $driverName = $config['driver'];
        if (array_key_exists($driverName, self::$adapter_class) === false) {
            throw new \Exception('Driver indicato non presente');
        }
        $instance = self::$adapter_class[$driverName];
        return new $instance($config);
    }

}

class MysqlAdapter implements AdapterInterface {

    /**
     *
     * @var \PDO 
     */
    protected $pdo;
    protected $dsn;
    protected $username;
    protected $password;

    public function __construct($config) {

        if ((!array_key_exists('username', $config)) ||
                (!array_key_exists('password', $config)) ||
                (!array_key_exists('database', $config))) {
            throw new Exception('manca uno dei parametri username/password/database');
        }
        $placeholder = new smn\lazyc\dbc\Helper\PlaceHolderSystem();
        $username = $config['username'];
        $password = $config['password'];
        $database = $config['database'];
        $this->username = $username;
        $this->password = $password;
        $placeholder->setPlaceHolder('database', $database);

        if ((!array_key_exists('hostname', $config)) && (!array_key_exists('sock', $config))) {
            return; // throw error mancano hostname o socket
        }

        $dsn_pattern = 'mysql:host={hostname};port={port};dbname={database}';
        if (array_key_exists('sock', $config)) {
            $dsn_pattern = 'mysql:unix_socket={socket};dbname={database}';
            $socket = $config['socket'];
            $placeholder->setPlaceHolder('socket', $socket);
        } else {
            $hostname = $config['hostname'];
            $port = (array_key_exists('port', $config)) ? $config['port'] : 3306;
            $placeholder->setPlaceHolder('hostname', $hostname);
            $placeholder->setPlaceHolder('port', $port);
        }
        $placeholder->setPattern($dsn_pattern);
        $dsn = $placeholder->render();
        $this->dsn = $dsn;
        $this->connect();
    }

    public function connect() {
        $this->pdo = new PDO($this->dsn, $this->username, $this->password);
    }

    public function disconnect() {
        $this->pdo = null;
    }

    public function getAllObjects() {

        /**
         * [object_class] => nome classe schema
         * [object_name] => nome schema
         * [object_child] =>
         *      [object_class]
         *      [object_name]
         *      [object_child]
         * [object_related]
         *      [object_class]
         *      [object_name]
         *      [object_child]
         *      [object_related]
         * [object_character_set]
         * @return type
         * 
         * 
         * [structure]
         *  [catalog_objects]
         *      [0]
         *        [name]
         *        [type]
         *        [encoding]
         *        [childs]
         *          [0]
         *            [name]
         *            [type]
         *            [encoding]
         *            [childs]
         *              [0]
         *                [name]
         *                [type]
         *                [encoding]
         *            [constraint]
         *              
         *  [constraint]
         *     [0]
         *       [type]
         *       [columns]
         *         [0] => [fqcon] => fully qualified catalog object name
         *       [options]
         * 
         */
        $structure = [];
        $query = 'select * from information_schema.collations';
        $stmt = $this->getPDOInstance()->prepare($query);
        $stmt->execute();
        $character_sets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        /**
         *     [COLLATION_NAME] => big5_chinese_ci
          [CHARACTER_SET_NAME] => big5
          [ID] => 1
          [IS_DEFAULT] => Yes
          [IS_COMPILED] => Yes
          [SORTLEN] => 1
         */
        $query = 'select * from information_schema.schemata where schema_name != ?';
        $stmt_schema = $this->getPDOInstance()->prepare($query);
        $stmt_schema->execute(['information_schema']);
        $schemas = $stmt_schema->fetchAll(PDO::FETCH_ASSOC);

// get tables
        $query = 'select * from information_schema.tables where table_type = ? and table_schema = ?';
        $stmt_tables = $this->getPDOInstance()->prepare($query);

        $query = 'select * from information_schema.columns where table_schema = ? and table_name = ?';
        $stmt_columns = $this->getPDOInstance()->prepare($query);




        $x = 0;
// schema, table, column
        foreach ($schemas as $schema) {
// get schemas
            $schema_name = $schema['SCHEMA_NAME'];
            $schema_character = $schema['DEFAULT_CHARACTER_SET_NAME'];
            $structure[$x] = [
                'type' => \smn\lazyc\dbc\Catalog\Schema::class,
                'name' => $schema_name,
                'encoding' => $schema_character
            ];
            $stmt_tables->execute(['BASE TABLE', $schema_name]);
            $tables = $stmt_tables->fetchAll(PDO::FETCH_ASSOC);
// get tables
            foreach ($tables as $table) {
                $table_name = $table['TABLE_NAME'];
                $table_collation = $table['TABLE_COLLATION'];
                $table_character = preg_grep('/^$/', array_map(function($value) use ($table_collation) {
                            if ($value['COLLATION_NAME'] == $table_collation) {
                                return $value['CHARACTER_SET_NAME'];
                            }
                        }, $character_sets), PREG_GREP_INVERT);
                $table_character = end($table_character);
                $table_struct = [
                    'type' => smn\lazyc\dbc\Catalog\Table::class,
                    'name' => $table_name,
                    'encoding' => $table_character
                ];

                $stmt_columns->execute([$schema_name, $table_name]);
                $columns = $stmt_columns->fetchAll(PDO::FETCH_ASSOC);

                $constraints = [];

                foreach ($columns as $column) {
                    $column_name = $column['COLUMN_NAME'];
                    $column_character = $column['CHARACTER_SET_NAME'];
                    $column_struct = [
                        'type' => smn\lazyc\dbc\Catalog\Column::class,
                        'name' => $column_name,
                        'encoding' => $column_character
                    ];
                    $table_struct['object_child'][] = $column_struct;
                    $constraint = [];
// default constraint
                    if (isset($column['COLUMN_DEFAULT'])) {
                        $constraint['name'] = 'DEFAULT';
                        $constraint['type'] = smn\lazyc\dbc\Catalog\Constraint\DefaultConstraint::class;
                        $constraint['columns'][] = $column_name;
                        $constraint['table'] = $column['TABLE_NAME'];
                        $constraints[] = $constraint;
                    }
// get not null
                    if ($column['IS_NULLABLE'] == 'NO') {
                        $constraint['name'] = 'NotNull';
                        $constraint['type'] = \smn\lazyc\dbc\Catalog\Constraint\NotNullConstraint::class;
                        $constraint['columns'][] = $column_name;
                        $constraint['table'] = $column['TABLE_NAME'];
                        $constraints[] = $constraint;
                    }
                }
// get primary key

                $query = 'SELECT k.CONSTRAINT_NAME,k.CONSTRAINT_SCHEMA, k.TABLE_NAME, k.COLUMN_NAME FROM '
                        . 'information_schema.KEY_COLUMN_USAGE k, information_schema.table_constraints t '
                        . 'where k.constraint_schema = t.constraint_schema '
                        . 'and k.table_schema = t.table_schema '
                        . 'and k.constraint_name = t.constraint_name '
                        . 'and k.table_name = t.table_name '
                        . 'and k.constraint_schema = ? '
                        . 'and t.constraint_type = ? '
                        . 'and t.table_name = ?';
                $stmt_pk = $this->getPDOInstance()->prepare($query);
                $stmt_pk->execute([$schema_name, 'PRIMARY KEY', $table_name]);
                $pkeys = $stmt_pk->fetchAll(PDO::FETCH_ASSOC);
                $constraint = [];
                foreach ($pkeys as $pkey) {
                    $constraint['name'] = $pkey['CONSTRAINT_NAME'];
                    $constraint['type'] = \smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraint::class;
                    $constraint['columns'][] = $pkey['COLUMN_NAME'];
                    $constraint['table'] = $pkey['TABLE_NAME'];
                }

                if (count($constraint) > 0) {
                    $constraints[] = $constraint;
                }

// get unique
                $query = 'SELECT k.CONSTRAINT_NAME,k.CONSTRAINT_SCHEMA, k.TABLE_NAME, k.COLUMN_NAME FROM '
                        . 'information_schema.KEY_COLUMN_USAGE k, information_schema.table_constraints t '
                        . 'where k.constraint_schema = t.constraint_schema '
                        . 'and k.table_schema = t.table_schema '
                        . 'and k.constraint_name = t.constraint_name '
                        . 'and k.table_name = t.table_name '
                        . 'and k.constraint_schema = ? '
                        . 'and t.constraint_type = ? '
                        . 'and t.table_name = ?'
                        . 'order by k.ordinal_position asc';
                $stmt_uq = $this->getPDOInstance()->prepare($query);
                $stmt_uq->execute([$schema_name, 'UNIQUE', $table_name]);
                $c_unique = $stmt_uq->fetchAll(PDO::FETCH_ASSOC);
                $constraint = [];
                foreach ($c_unique as $unique) {
                    $constraint['name'] = $unique['CONSTRAINT_NAME'];
                    $constraint['type'] = smn\lazyc\dbc\Catalog\Constraint\UniqueConstraintInterface::class;
                    $constraint['columns'][] = $unique['COLUMN_NAME'];
                    $constraint['table'] = $unique['TABLE_NAME'];
                }

                if (count($constraint) > 0) {
                    $constraints[] = $constraint;
                }

// get check

                $query = 'select c.CONSTRAINT_NAME, c.CHECK_CLAUSE, t.TABLE_NAME '
                        . 'from information_schema.TABLE_CONSTRAINTS t, information_schema.CHECK_CONSTRAINTS c '
                        . 'where t.constraint_schema = c.constraint_schema '
                        . 'and t.constraint_name = c.constraint_name '
                        . 'and c.constraint_schema = ? '
                        . 'and t.table_name = ?';
                $stmt_check = $this->getPDOInstance()->prepare($query);
                $stmt_check->execute([$schema_name, $table_name]);
                $checks = $stmt_check->fetchAll(PDO::FETCH_ASSOC);
                $constraint = [];
                foreach ($checks as $check) {
                    $constraint['name'] = $check['CONSTRAINT_NAME'];
                    $constraint['type'] = \smn\lazyc\dbc\Catalog\Constraint\CheckConstraint::class;
                    $constraint['expression'] = $check['CHECK_CLAUSE'];
                    $constraint['table'] = $check['TABLE_NAME'];
                    $constraints[] = $constraint;
                }


// get foreign key

                $query = 'SELECT k.CONSTRAINT_SCHEMA as SCHEMA_NAME, '
                        . 'k.CONSTRAINT_NAME as CONSTRAINT_NAME, '
                        . 'k.TABLE_SCHEMA as TABLE_SCHEMA, '
                        . 'k.TABLE_NAME as TABLE_NAME, '
                        . 'k.COLUMN_NAME as COLUMN_NAME, '
                        . 'k.REFERENCED_TABLE_SCHEMA as SCHEMA_REFERENCE, '
                        . 'k.REFERENCED_TABLE_NAME as TABLE_REFERENCE, '
                        . 'k.REFERENCED_COLUMN_NAME as COLUMN_REFERENCE '
                        . 'FROM information_schema.KEY_COLUMN_USAGE k, information_schema.table_constraints t '
                        . 'where k.constraint_schema = t.constraint_schema '
                        . 'and k.table_schema = t.table_schema '
                        . 'and k.constraint_name = t.constraint_name '
                        . 'and k.table_name = t.table_name '
                        . 'and t.constraint_type = ? '
                        . 'and t.CONSTRAINT_SCHEMA = ? '
                        . 'and k.TABLE_NAME = ?';
                $stmt_fk = $this->getPDOInstance()->prepare($query);
                $stmt_fk->execute(['FOREIGN KEY', $schema_name, $table_name]);
                $fkeys = $stmt_fk->fetchAll(PDO::FETCH_ASSOC);
                $constraint = [];

                foreach ($fkeys as $fkey) {
                    $constraint['name'] = $fkey['CONSTRAINT_NAME'];
                    $constraint['type'] = \smn\lazyc\dbc\Catalog\Constraint\ForeignKeyConstraint::class;
                    $constraint['schema'] = $fkey['SCHEMA_NAME'];
                    $constraint['table'] = $fkey['TABLE_NAME'];
                    $constraint['column'] = $fkey['COLUMN_NAME'];
                    $constraint['schema_ref'] = $fkey['SCHEMA_REFERENCE'];
                    $constraint['table_ref'] = $fkey['TABLE_REFERENCE'];
                    $constraint['column_ref'] = $fkey['COLUMN_REFERENCE'];
                    $constraints[] = $constraint;
                }

// get index key (sono tutti gli incidi per ordinamento che non sono pk, unique, fk, check, etc)



                $query = 'select * from information_schema.STATISTICS s '
                        . 'where s.index_name not in ('
                        . 'select constraint_name from information_schema.table_constraints t '
                        . 'where t.constraint_schema = ? '
                        . 'and t.table_name = ? '
                        . ') '
                        . 'and s.index_schema = ? '
                        . 'and s.table_name = ? ';
                $query_index_name = sprintf('select distinct i.index_name from (%s) i', $query);

                $stmt_indexes_name = $this->getPDOInstance()->prepare($query_index_name);
                $stmt_indexes_name->execute([$schema_name, $table_name, $schema_name, $table_name]);

                $indexes_name = $stmt_indexes_name->fetchAll(PDO::FETCH_ASSOC);

                $query = 'select * from information_schema.STATISTICS s '
                        . 'where s.index_name not in ('
                        . 'select constraint_name from information_schema.table_constraints t '
                        . 'where t.constraint_schema = ? '
                        . 'and t.table_name = ? '
                        . ') '
                        . 'and s.index_schema = ? '
                        . 'and s.table_name = ? '
                        . 'and s.index_name = ? ';
                $stmt_index = $this->getPDOInstance()->prepare($query);
                foreach ($indexes_name as $index_name) {
                    $stmt_index->execute([$schema_name, $table_name, $schema_name, $table_name, $index_name['INDEX_NAME']]);
                    $index_of_table = $stmt_index->fetchAll(PDO::FETCH_ASSOC);
                    $constraint = [];
                    foreach ($index_of_table as $idx) {
                        $constraint['name'] = $idx['INDEX_NAME'];
                        $constraint['type'] = smn\lazyc\dbc\Catalog\Constraint\IndexConstraint::class;
                        $constraint['columns'][] = $idx['COLUMN_NAME'];
                    }
                    if (count($constraint) > 0) {
                        $constraints[] = $constraint;
                    }
                }

                $table_struct['constraints'] = $constraints;
                $structure[$x]['object_child'][] = $table_struct;
            }
            $x++;
        }





        return $structure;
    }

    public function getDbName() {
        $result = $this->pdo->query('select database()');
        return $result->fetchAll()[0][0]; // aggiusta...sembrano due tette
    }

    public function getStatement() {
        
    }

    /**
     * 
     * @return PDO
     */
    public function getPDOInstance() {
        return $this->pdo;
    }

    /**
     * 
     * @param type $structure
     * @return smn\lazyc\dbc\Catalog\SchemaInterface[]
     */
    public function makeStructure($structure) {
        $render = [];
        foreach ($structure as $s) {
            $type = $s['type'];
            $name = $s['name'];
            unset($s['type']);
            unset($s['name']);
            $options = array_merge([], $s);
            $render[] = call_user_func_array([$type, 'createCatalogObjectInstance'], [$name, $options]);
        }
        return $render;
    }

}

$test = [
    'type' => smn\lazyc\dbc\Catalog\Schema::class,
    'name' => 'ofpro',
    'encoding' => 'utf8mb4',
    'object_child' => [
        [
            'type' => smn\lazyc\dbc\Catalog\Table::class,
            'name' => 'apm',
            'encoding' => 'utf8mb4',
            'object_child' => []
        ]
    ]
];



$info = [
    'hostname' => '192.168.56.101',
    'port' => 3306,
    'username' => 'root',
    'password' => '',
    'database' => 'ofpro',
    'driver' => 'pdo_mysql'
];
$adapter = DBC::getAdapter($info, []);
$structure = $adapter->getAllObjects();
$schemas = $adapter->makeStructure($structure);


$ofpro = $schemas[0];
foreach($ofpro->getAllTables() as $table) {
    echo $table->getName() .'<br>';
    foreach($table->getAllColumns() as $column) {
        echo sprintf('%s<br>', $column->getName());
    }
    echo '<br>';
}


