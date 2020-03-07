<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace smn\lazyc\dbc\Catalog;
use smn\lazyc\dbc\Catalog\CatalogObject;
use smn\lazyc\dbc\Catalog\PrintableInterface;
use smn\lazyc\dbc\Catalog\Table;
use smn\lazyc\dbc\Helper\PlaceHolderSystem;
use smn\lazyc\dbc\Catalog\Constraint\Constraint;
use smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraintInterface;
/**
 * Description of Table
 *
 * @author A760526
 */

class Table extends CatalogObject implements PrintableInterface, TableInterface {

    /**
     * La constante TYPENAME definisce il tipo di oggetto della classe
     */
    const TYPENAME = self::CATALOG_OBJECT_TABLE;

    /**
     * Contiene un'istanza di PlaceHolderSystem per stampare il nome dello schema
     * @var PlaceHolderSystem 
     */
    protected $ph;

    /**
     * Costruttore della classe. Istanzia un oggetto di catalogo di tipo Table
     * @param string $name Nome della tabella
     */
    public function __construct($name) {
        parent::__construct($name, Table::TYPENAME);
        $this->ph = new PlaceHolderSystem();
        $this->ph->setPattern('{table}');
        $this->ph->setParam('catalog_object', $this);
        $this->ph->setPlaceHolder('table', function(PlaceHolderSystem $ph) {
            $co = $ph->getParam('catalog_object');
            return $co->getName();
        });
    }

    /**
     * Restituisce il nome della tabella. Se la tabella è associata ad un database,
     * restituisce anche il nome del database nel formato schema.table
     * @return type
     */
    public function toString() {
        if ($this->getParent() !== null) {
            $this->ph->setPattern('{inherit}.{table}');
            $this->ph->setPlaceHolder('inherit', function(PlaceHolderSystem $ph) {
                $co = $ph->getParam('catalog_object');
                return $co->getParent()->toString();
            });
        }
        return $this->ph->render();
    }

    /**
     * Restituisce l'istanza Schema padre
     * @return SchemaInterface
     */
    public function getSchema() {
        return $this->getParent();
    }

    /**
     * Configura lo schema padre
     * @param SchemaInterface $schema
     */
    public function setSchema(SchemaInterface $schema) {
        $this->setParent($schema);
    }

    /**
     * Aggiunge una colonna alla tabella. Se la colonna già esiste non sarà aggiunta
     * @param ColumnInterface $column Istanza della colonna da aggiungere
     */
    public function addColumn(ColumnInterface $column) {
        $this->addChild($column);
    }

    /**
     * Restituisce tutte le colonne della tabella
     * @return ColumnInterface[]
     */
    public function getAllColumns() {
        return $this->getChildren(Column::TYPENAME);
    }

    /**
     * Restituisce una colonna della tabella avente nome $column
     * @param string $column Nome della colonna
     * @return ColumnInterface
     */
    public function getColumn(string $column) {
        return $this->getChild($column, Column::TYPENAME);
    }

    /**
     * Restituisce l'istanza PrimaryKeyConstraint che rappresenta la Primary Key<br>
     * di questa tabella
     * @return PrimaryKeyConstraintInterface
     */
    public function getPrimaryKey() {
        $constraints = []; // fake array per evitare un if nel caso in cui la tabella non ha pk
        foreach ($this->getAllColumns() as $column) {
            if ($column->isPrimaryKey()) {
                $constraints = $column->getConstraints();
                break;
            }
        }
        foreach ($constraints as $constraint) {
            if ($constraint->getType() == Constraint::CONSTRAINT_PK) {
                return $constraint;
            }
        }
        return false;
    }

    /**
     * Restituisce true o false se la colonna esiste
     * @param string $column
     * @return bool
     */
    public function hasColumn(string $column) {
        return $this->hasChild($column, Column::TYPENAME);
    }

    /**
     * Rimuove la colonna $column
     * @param string $column
     */
    public function removeColumn(string $column) {
        $this->removeChild($column, Column::TYPENAME);
    }
    
    public static function createCatalogObjectInstance(string $name, $options = array()) {
        $table = parent::createCatalogObjectInstance($name, $options);
        if (array_key_exists('constraints', $options)) {
            $constraints = $options['constraints'];
            foreach($constraints as $constraint) {
                // factory method per constraint
            }
        }
        return $table;
    }

}
