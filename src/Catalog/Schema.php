<?php


namespace smn\lazyc\dbc\Catalog;
use smn\lazyc\dbc\Catalog\CatalogObject;
use smn\lazyc\dbc\Catalog\PrintableInterface;
use smn\lazyc\dbc\Helper\PlaceHolderSystem;
/**
 * La classe schema rappresenta uno schema di un database generico.
 *
 * @author A760526
 */

class Schema extends CatalogObject implements PrintableInterface, SchemaInterface {

    
    /**
     * La constante TYPENAME definisce il tipo di oggetto della classe
     */
    const TYPENAME = self::CATALOG_OBJECT_SCHEMA;

    /**
     * Contiene un'istanza di PlaceHolderSystem per stampare il nome dello schema
     * @var PlaceHolderSystem 
     */
    protected $ph;

    /**
     * Costruttore, crea un oggetto di tipo schema
     * @param String $name Nome dello schema
     */
    public function __construct($name) {
        parent::__construct($name, 'schema');

        $this->ph = new PlaceHolderSystem();
        $this->ph->setPattern('{schema}');
        $this->ph->setParam('catalog_object', $this);
        $this->ph->setPlaceHolder('schema', function(PlaceHolderSystem $ph) {
            $co = $ph->getParam('catalog_object');
            return $co->getName();
        });
    }

    /**
     * Restituisce il nome dello schema
     * @return string
     */
    public function toString() {
        return $this->ph->render();
    }

    /**
     * Metodo per aggiungere una tabella allo schema. Può essere usato anche<br>
     * CatalogObjectInterface::addChild() , che aggiunge un oggetto generico di catalogo
     * @param TableInterface $table
     */
    public function addTable(TableInterface $table) {
        $this->addChild($table);
    }

    /**
     * Restituisce tutte le tabelle dello schema
     * @return TableInterface[]
     */
    public function getAllTables() {
        return $this->getChildren(Table::TYPENAME);
    }

    /**
     * Restituisce la tabella $table se esiste
     * @param string $table
     * @return TableInterface
     */
    public function getTable(string $table) {
        return $this->getChild($table, Table::TYPENAME);
    }

    /**
     * Restituisce true o false se la tabella $table esiste
     * @param string $table
     * @return bool
     */
    public function hasTable(string $table) {
        return $this->hasChild($table, Table::TYPENAME);
    }

    /**
     * Rimuove la tabella $table dalla lista delle tabelle dello schema
     * @param string $table
     */
    public function removeTable(string $table) {
        $this->removeChild($table, Table::TYPENAME);
    }

}

