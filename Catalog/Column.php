<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace smn\lazyc\dbc\Catalog;
use smn\lazyc\dbc\Catalog\CatalogObject;
use smn\lazyc\dbc\Catalog\PrintableInterface;
use smn\lazyc\dbc\Catalog\ColumnInterface;
use smn\lazyc\dbc\Catalog\Constraint\ConstraintInterface;
use smn\lazyc\dbc\Helper\PlaceHolderSystem;
use smn\lazyc\dbc\Catalog\Constraint\Constraint;
/**
 * La classe Column rappresenta una colonna di un database.
 *
 * @author A760526
 */
class Column extends CatalogObject implements PrintableInterface, ColumnInterface {

    /**
     * Variabile placeholder system. Utile per utilizzare gli operatori sulla colonna
     * @var PlaceHolderSystem 
     */
    protected $ph;

    /**
     * La constante TYPENAME definisce il tipo di oggetto della classe
     */
    const TYPENAME = self::CATALOG_OBJECT_COLUMN;

    /**
     * Array con la lista delle constraint associate a questa tabella
     * @var ConstraintInterface[] 
     */
    protected $constraints = [];

    /**
     * Costruttore della classe
     * @param type $name
     * @param type $value
     */
    public function __construct($name) {
        parent::__construct($name, 'column');
        $this->ph = new PlaceHolderSystem();
        $this->ph->setPattern('{column}');
        $this->ph->setParam('catalog_object', $this);
        $this->ph->setPlaceHolder('column', function(PlaceHolderSystem $ph) {
            $co = $ph->getParam('catalog_object');
            return $co->getName();
        });

    }

    /**
     * Restituisce una stringa che rappresenta il nome della colonna. Se la colonna
     * è associata ad una tabella (a sua volta associata ad uno schema) la stringa
     * restituita comprenderà schema.table.column
     * Se si vuole conoscere solo il nome della colonna usare il metodo getName()
     * @return String
     */
    public function toString() {
        if ($this->getParent() !== null) {
            $this->ph->setPattern('{inherit}.{column}');
            $this->ph->setPlaceHolder('inherit', function(PlaceHolderSystem $ph) {
                $co = $ph->getParam('catalog_object');
                return $co->getParent()->toString();
            });
        }
        return $this->ph->render();
    }

    /**
     * Configura la tabella d'appartenenza della colonna
     * @param TableInterface $table
     */
    public function setTable(TableInterface $table) {
        $this->setParent($table);
    }

    /**
     * Restituisce la tabella d'appartenenza della colonna
     * @return TableInterface 
     */
    public function getTable() {
        return $this->getParent();
    }

    /**
     * Restituisce true o false se la colonna è associata ad una PK
     * @return boolean
     */
    public function isPrimaryKey() {
        $types = array_map(function(ConstraintInterface $constraint) {
            return $constraint->getType();
        }, $this->constraints);
        return (array_search(Constraint::CONSTRAINT_PK, $types) === false) ? false : true;
    }

    /**
     * Restituisce true o false se la colonna è Not Null
     * @return bool
     */
    public function isNotNull() {
        $types = array_map(function(ConstraintInterface $constraint) {
            return $constraint->getType();
        }, $this->constraints);
        return (array_search(Constraint::CONSTRAINT_NOT_NULL, $types) === false) ? false : true;
    }

    /**
     * Configura una constraint ad una colonna
     * @param ConstraintInterface $constraint
     */
    public function addConstraint(ConstraintInterface $constraint) {
        // se la constraint non esiste già, la aggiungo
        if (array_search($constraint, $this->constraints, true) === false) {
            $this->constraints[] = $constraint;
            // dico alla constraint di associare la colonna
            // probabile problema di memory allowed ?
            $constraint->relationTo($this);
        }
    }

    /**
     * Restituisce la lista delle constraint associate alla colonna
     * @return ConstraintInterface[]
     */
    public function getConstraints() {
        return $this->constraints;
    }

    // servono metodi per rimuovere le constraint
}
