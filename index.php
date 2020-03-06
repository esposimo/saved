<?php

// EUC-JP
// SJIS
// eucJP-win
// EUC-JP-2004
// SJIS-win
// SJIS-Mobile#DOCOMO
// SJIS-Mobile#KDDI
// SJIS-Mobile#SOFTBANK
// SJIS-mac
// SJIS-2004
// JIS
// ISO-2022-JP
// ISO-2022-JP-MS
// ISO-2022-JP-2004
// ISO-2022-JP-MOBILE#KDDI


/**
 * 
 * bisogna tenere conto quindi del 
 *  character set in fase di  connessione
 *  character set di ogni colonna
 * 
 * se il character set di una colonna, è diverso dal character set della connessione
 *  è necessario fare una conversione
 * 
 * è necessario inoltre capire per ogni character set a che alias fa riferimento (cioè, viceversa)
 * 
 * se una colonna ha un character set diverso da quello della connessione (default o preimpostato)
 * bisogna prevedere una conversione in fase di estrazione dati oppure di inserimento
 * 
 * quando inserisco, la colonna del valore deve essere trasformata, almeno in mysql, in
 *  binary(?) e nell'inserimento usare mb_convert_conding(stringa, character set destinazione, character set sorgente)
 * 
 * in caso di estrazione invece
 * 
 * usare ella query di select binary(campo) (sempre in mysql) e poi usare mb_convert_encoding per avere i caratteri
 * nel character set che serve
 * 
 * 
 * 
 */
$in_charset = 'utf-8';
$out_charset = 'Windows-1254';
$out_charset = 'ISO-2022-JP';
$out_charset = 'utf-16';
$out_charset = 'ISO-8859-1';
$out_charset = 'SJIS';
$out_charset = 'UTF-8';
header('Content-type: text/html; charset=' . $out_charset);
//mb_detect_order(array_merge(['ASCII','UTF-8'],mb_list_encodings()));
//mb_detect_order(array_merge(['ASCII','UTF-8'],mb_list_encodings()));
//
//die();
//
//$pdo = new PDO('mysql:host=192.168.56.102;dbname=of', 'root', 'of');
//$statement = $pdo->prepare('insert into f(field) values(binary(?))');
//$string = chr(0xc0) .chr(0xc1) .chr(0xc0) .chr(0xc3) .chr(0xc6) .chr(0xd0) .chr(0xc9);
//$values = [ mb_convert_encoding(mb_chr(0xff80), 'SJIS','UTF-8') ];
////$statement->execute($values);
//
//
//
//echo '<pre>';
//print_r($statement->errorInfo());
//echo '</pre>';
//die();
//
//$query = 'show variables like "character_set_database"'; // query per conoscere il char set della connessione (istanza PDO) 
//$query = 'select binary(field) from f'; // mi prendo il campo che ha un character set diverso in formato binario
//$result = $pdo->query($query);
//
//$rows = $result->fetchAll();
//$chinese_value = $rows[0][0];
//
//echo mb_convert_encoding($chinese_value, 'UTF-8','SJIS'); // sapendo che quello è un sjis, lo converto in utf8
//
//echo '<pre>';
//print_r(mb_encoding_aliases('iso-8859-1'));
//echo '</pre>';
//
//die();
//echo dechex(ord(substr(mb_chr($chr),0,1))) .'<br>';
//echo dechex(ord(substr(mb_chr($chr),1,1))) .'<br>';
//echo dechex(ord(substr(mb_chr($chr),2,1))) .'<br>';
//
//
//die();
//
//
//echo chr(192);
//echo '<br>';
//echo iconv('UTF-8','SJIS',chr(0xc0));
//echo '<br>';
//echo 'mb_ord => ' .mb_ord('SJIS');
//echo '<br>';
//
//echo '<pre>';
//print_r(unpack('V',0xc0));
//echo '</pre>';
//echo (($char = mb_chr(0xc0,$out_charset)) === false) ? 'fallito' : $char;
//echo '<br>';
//echo (($char = mb_chr(0x30a2,$out_charset)) === false) ? 'fallito' : $char;
//echo '<br>';
//
//
//die();
//echo '<pre>';
//print_r(mb_list_encodings());
//echo '</pre>';
//foreach(mb_list_encodings() as $encode) {
//    
//    echo $encode .' => ' .mb_chr(0x1bc0,'UTF-8') .'<br>';
//    
//}
//
//echo '<pre>';
//print_r(mb_get_info());
//echo '</pre>';
//die();
//
//
//for($i=0x80;$i<0xE0;$i++) {
//    
//echo $i .' =>';
//echo (($char = mb_chr($i,$out_charset)) === false) ? 'fallito' : $char;
//echo '<br>';
//}
//
//echo mb_ord(mb_chr(0xff80,'UTF-8'),'shift-jis');
//
//foreach(mb_list_encodings() as $encode) {
//    
//    echo $encode .' => ' .mb_ord(mb_chr(0xff80,'UTF-8'),$encode) .'<br>';
//    
//}
//
//die();
////echo mb_convert_encoding(mb_chr(uxc0, 'shift-jis'), 'UTF-8', 'shift-jis');
//
//
//
////echo (($char = mb_chr(0xff80,$out_charset)) === false) ? 'fallito' : $char;
//
//
//
//
////echo mb_convert_encoding($str, $out_charset);
//echo iconv('SJIS', 'UTF-8', 'ﾀ');
//echo '<pre>';
//print_r(mb_list_encodings());
//echo '</pre>';
//
//
//
//
//
//echo '</body>';
//
//
//
//
//
//
//die();
////echo mb_strlen('Ď','iso-8859-1');
//echo mb_convert_encoding('Ď','ISO-8859-1','UTF-8');
//echo '<br>';
//echo 'mb_strlen() ISO-8859-1 Ď => ' .mb_strlen('Ď','ISO-8859-1');
//echo '<br>';
//echo 'mb_strlen() UTF-8 Ď => ' .mb_strlen('Ď','UTF-8');
//echo '<br>';
//echo 'mb_asc() ISO-8859-1 Ď => ' .mb_ord('Ď','ISO-8859-1');
//echo '<br>';
//echo 'mb_asc() UTF-8 Ď => ' .mb_ord('Ď','UTF-8');
//echo '<br>';
//echo 'mb_asc() EUC-JP-2004 Ď => ' .mb_ord('Ď','EUC-JP-2004');
//echo '<br>';
//echo 'mb_asc() UTF8 A => ' .mb_ord('A','UTF8');
//echo '<br>';
//echo 'mb_asc() ISO-8859-1 A => ' .mb_ord('A','ISO-8859-1');
//echo '<br>';
//
//
//echo iconv('UTF-8','UCS-4LE','؁');
//echo iconv('Windows-1254','UTF-8',mb_chr(0xca,'Windows-1254'));
//
//echo '<br>we ';
//echo mb_ord(mb_chr(0xc0,'SJIS-2004'),'SJIS-2004');
//
//echo mb_chr(192,'UTF-8');
//
//echo '<pre>';
//print_r(mb_detect_order());
//echo '</pre>';
//
//
//echo '<pre>';
//print_r(unpack('V', mb_convert_encoding(mb_chr(0xc0,'SJIS-2004'),'UCS-4LE', 'SJIS-2004')));
//echo '<pre>';
//
//
//
////echo '<pre>';
////print_r(unpack('V',iconv('UTF-8','UCS-4LE','؁')));
////echo '</pre>';
//
//
//echo '<pre>';
//print_r(mb_list_encodings());
//echo '</pre>';
//
//
//
//die();


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

//
///**
// * Una struttura tale che 
// * quando aggiungo un figlio
// *  se il figlio non è già un nodo, lo aggiungo semplicemente e dico lui chi è il padre e a che livello si trova
// *  se il figlio è già un nodo, l'istanza e le do un nuovo padre e un nuovo livello e avviso i suoi figli che il  livello
// *  è cambiato
// * 
// */
//$node = new Node('root');
//$node->addNode('child1');
//$node->addNode('child2');
//$node->addNode('child3');
//$node->addNode('child4');
//
//$child3 = $node->getNode(2);
////$child3->setParent('newroot');
//$child3->addNode('child3_1');
//$child3->addNode('child3_2');
//$child3->addNode('child3_3');
//$node->getNodeByValue('child3')->getNodeByValue('child3_1')->addNode('child3_1_1');
interface BindableInterface {

    /**
     * Imposta il nome da bindare
     * @param String $name
     * @return self
     */
    public function setBindName(String $name);

    /**
     * Imposta il valore da bindare
     * @param Mixed $value
     * @return self
     */
    public function setBindValue($value);

    /**
     * Restituisce il nome bindato
     * @return String
     */
    public function getBindName();

    /**
     * Restituisce il valore bindato
     * @return Mixed
     */
    public function getBindValue();

    /**
     * Imposta la tipologia di parametro in base ai PDO::PARAM_*
     * @param Int
     * @return self
     */
    public function setTypeParam(int $type);

    /**
     * Restituisce il tipo di parametro
     * @return Int|Null
     */
    public function getTypeParam();

    /**
     * Imposta a true o false l'autodeterminazione della colonna in base
     * al bind value assegnato
     * @param Bool $auto_type
     * @return self
     */
    public function enableAutoType(bool $auto_type = true);
}

interface BindableObjectInterface {

    /**
     * 
     * @return BindableInterface[]
     */
    public function getBindParams();
}

class Bindable implements BindableInterface {

    /**
     * Unique id per rendere univoco il parametro all'interno di uno statement
     * @var String 
     */
    protected $uniqid;

    /**
     * Nome del parametro da bindare
     * @var String 
     */
    protected $name;

    /**
     * Valore da bindare
     * @var Mixed 
     */
    protected $value;

    /**
     * Costante che definisce il tipo di parametro. Corrisponde ai valori
     * delle PDO::PARAM_*
     * @var Int 
     */
    protected $type = PDO::PARAM_STR;

    /**
     * Definisce se la definizione del tipo deve essere automatica o meno
     * @var Bool 
     */
    protected $auto_type = true;

    /**
     * Inizializza la classe
     * @param String $name
     * @param Mixed $value
     */
    public function __construct(String $name, $value = null) {
        $this->uniqid = uniqid();
        $this->setBindName($name);
        if (!is_null($value)) {
            $this->setBindValue($value);
            $this->setTypeParam(Bindable::checkTypeColumn($value));
        }
    }

    /**
     * Configura l'auto type dell'oggetto
     * @param bool $auto_type
     * @return self
     */
    public function enableAutoType($auto_type = true) {
        $this->auto_type = $auto_type;
        return $this;
    }

    /**
     * Restituisce il bindname
     * @return String
     */
    public function getBindName() {
        return sprintf(':%s_%s', $this->uniqid, $this->name);
    }

    /**
     * Restituisce il valore da bindare
     * @return Mixed
     */
    public function getBindValue() {
        return $this->value;
    }

    /**
     * Restituisce il tipo di parametro
     * @return Int
     */
    public function getTypeParam() {
        return $this->type;
    }

    /**
     * Configura il nome del bind
     * @param String $name
     * @return self
     */
    public function setBindName(String $name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Configura il valore del bind
     * @param Mixed $value
     * @return self
     */
    public function setBindValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * Configura il tipo di parametro
     * @param type $type
     * @return self
     */
    public function setTypeParam($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * Metodo privato che autodetermina il tipo di dato da bindare
     * @return Int
     */
    public static function checkTypeColumn($value) {
        if (is_string($value)) {
            $param_type = PDO::PARAM_STR;
        } else if (is_numeric($value)) {
            if (is_int($value)) {
                $param_type = PDO::PARAM_INT;
            } else {
                $param_type = PDO::PARAM_STR;
            }
        } else if (is_null($value)) {
            $param_type = PDO::PARAM_NULL;
        } else if (is_bool($value)) {
            $param_type = PDO::PARAM_BOOL;
        } else if (is_resource($value())) {
            $param_type = PDO::PARAM_LOB;
        } else {
            $param_type = PDO::PARAM_STR;
        }
        return $param_type;
    }

    public function __clone() {
        $this->uniqid = uniqid();
    }

}

/**
 * La catalog Object prevede che un oggetto di catalogo abbia un nome, un tipo
 * ed eventuale parentela
 */

/**
 * La colonna si occupa di , appunto, rappresentare una colonna.
 * Ad essa sono associate anche le constraint (di vario genere). Le constraint
 * pur appartenendo da una tabella, rappresentano una colonna, per tanto vanno 
 * associate alle Column. Una constraint può sapere a che tabella è associata
 * chiedendolo al metodo getTable della tabella
 * 
 */





// le variabili di tipo stringa storicizzano il numero di caratteri in base 
// al numero di caratteri effettivo del charact ser della colonna
// 
// le nchar storicizzano gli unicode utili .. ?
//
// per calcolare la lunghezza di una stringa in un char devo sapere 2 cose
// stringa
// charset di destinazione
// con questi due parametri, calcolo la lunghezza
//echo mb_strlen('aĄ','iso-8859-1');
//

















/**
 * 
 * creare interfacce per tipologia (numerico, char, data, binary)
 * ognuna deve sapere il PDO relativo per bindare un valore
 * 
 * 
 * 
 * 
 * 
 * 
 */

/**
 * quando farai le colonne intese come valori per gli statement
 * aggiungi un check sul tipo di dato prima di eseguire lo statement
 * in modo tale da verificare se il dato bindato è congruo
 * a quello della colonna
 * 
 * 
 */
//use smn\lazyc\dbc\Catalog\Schema;
//use smn\lazyc\dbc\Catalog\Table;
//use smn\lazyc\dbc\Catalog\Column;
//use smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraint;

//$schema = new Schema('schema');
//$schema->addChild(new Table('tabella'));
//
//$table = $schema->getTable('tabella');
//$column = new Column('oid');
//$column->setTable($schema->getTable('tabella'));
//
//$pkey = new PrimaryKeyConstraint([$column]);

/**
 * 
 * adapter deve
 * 
 *  
 * rivedere la questione dsn. ogni driver ha il suo formato
 * 
 * interfaccia Adapter wrappa l'adapter (PDO)
 * interfaccia statement wrappa lo statement (PDOStatement)
 * 
 */
interface AdapterInterfacekhh {

 
    
    
    public function connect();
    public function disconnect();
    
    public function getDbName();
    
    
    


    /**
     * 
     * [table] => [all tables]
     * [column] => 
     *      [table1] => [all columns]
     *      [table2] => [all columns]
     * [constraint]
     *      [type]
     *          [] => [columns]
     *          [] => [columns]
     * [triggers]
     *      [] => triggers
     */
}

/**
 * 
 * hostname
 * port
 * db
 * user
 * password
 * driver
 * charset
 * sock
 * 
 */
// pdo, mysql, mysqli, postgres, oracle, sql server
function array_keys_exists(array $keys, array $arr) {
   return !array_diff_key(array_flip($keys), $arr);
}
$keys = ['driver','hostname'];

$array = ['driver' => 1,'hostname' => 2,'pippo' => 3];


class Adapter {

    protected static $adapters = [
        'pdo_mysql' => MysqlPDOAdapter::class
    ];

    
}

interface DataInterface {

    public function setValue($value);

    public function getValue();

    public function setColumn(\smn\lazyc\dbc\Catalog\ColumnInterface $column);

    public function getColumn();
}

class Data implements DataInterface, BindableObjectInterface {

    protected $column;
    protected $value;
    protected $bind;

    public function __construct(\smn\lazyc\dbc\Catalog\ColumnInterface $column, $value) {
        $this->bind = new Bindable($column->getName(), $value);
        $this->setColumn($column);
        $this->setValue($value);
    }

    public function getColumn() {
        return $this->column;
    }

    public function getValue() {
        return $this->value;
    }

    public function setColumn(\smn\lazyc\dbc\Catalog\ColumnInterface $column) {
        $this->column = $column;
        $this->bind->setBindName($column->getName());
    }

    public function setValue($value) {
        $this->value = $value;
        $this->bind->setBindValue($value);
    }

    public function getBindParams() {
        return [$this->bind];
    }

}

// ricordati che tutte queste classi rappresentano gli oggetti di catalogo
// non i valori che possono esserci sul database

