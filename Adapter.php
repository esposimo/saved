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

        $query = 'select * from information_schema.table_constraints';
        $stmt_constraint = $this->getPDOInstance()->prepare($query);
        $stmt_constraint->execute();
        $constraints = $stmt_constraint->fetchAll(PDO::FETCH_ASSOC);


        $x = 0;
        foreach ($schemas as $schema) {
            $schema_name = $schema['SCHEMA_NAME'];
            $schema_character = $schema['DEFAULT_CHARACTER_SET_NAME'];
            $structure[$x] = [
                'object_class' => \smn\lazyc\dbc\Catalog\Schema::class,
                'object_name' => $schema_name,
                'object_character_set' => $schema_character
            ];
            $stmt_tables->execute(['BASE TABLE', $schema_name]);
            $tables = $stmt_tables->fetchAll(PDO::FETCH_ASSOC);
            // get tables
            foreach ($tables as $table) {
                $table_name = $table['TABLE_NAME'];
                $table_collation = $table['TABLE_COLLATION'];
                $table_character = array_pop(preg_grep('/^$/', array_map(function($value) use ($table_collation) {
                                    if ($value['COLLATION_NAME'] == $table_collation) {
                                        return $value['CHARACTER_SET_NAME'];
                                    }
                                }, $character_sets), PREG_GREP_INVERT));
                $table_struct = [
                    'object_class' => smn\lazyc\dbc\Catalog\Table::class,
                    'object_name' => $table_name,
                    'object_character_set' => $table_character
                ];

                $stmt_columns->execute([$schema_name,$table_name]);
                $columns = $stmt_columns->fetchAll(PDO::FETCH_ASSOC);

                foreach($columns as $column) {
                    $column_name = $column['COLUMN_NAME'];
                    $column_character = $column['CHARACTER_SET_NAME'];
                    $column_struct = [
                        'object_class' => smn\lazyc\dbc\Catalog\Column::class,
                        'object_name' => $column_name,
                        'object_character_set' => $column_character
                    ];
                    $table_struct['object_child'][] = $column_struct;
                }
                $structure[$x]['object_child'][] = $table_struct;
            }
            $x++;
        }
        echo '<pre>';
        print_r($structure);
        echo '</pre>';
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

}

$info = [
    'hostname' => '192.168.56.102',
    'port' => 3306,
    'username' => 'root',
    'password' => 'of',
    'database' => 'of',
    'driver' => 'pdo_mysql'
];
$adapter = DBC::getAdapter($info, []);
$pdo = $adapter->getPDOInstance();
$adapter->getAllObjects();

