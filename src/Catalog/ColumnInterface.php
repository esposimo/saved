<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog;

use smn\lazyc\dbc\Catalog\TableInterface;
use smn\lazyc\dbc\Catalog\Constraint\ConstraintInterface;

/**
 * L'interfaccia Column rappresenta una colonna del database. Estende la <br>
 * CatalogObjectInterface perchè è necessario avere a disposizione le funzioni<br>
 * di un oggetto di catalogo, come la gestione di padre e figli.
 * @author A760526
 */
interface ColumnInterface extends CatalogObjectInterface {

    /**
     * Configura la tabella alla quale appartiene la colonna
     * @param TableInterface $table 
     */
    public function setTable(TableInterface $table);

    /**
     * Restituisce la tabella alla quale appartiene la colonna
     * @return TableInterface
     */
    public function getTable();

    /**
     * Restituisce true/false se questa colonna è o fa parte delle colonne<br>
     * utilizzate come primary key della tabella
     * @return boolean
     */
    public function isPrimaryKey();

    /**
     * Restituisce true o false se questa colonna è impostata come Not Null
     * @return bool
     */
    public function isNotNull();

    /**
     * Restituisce tutte le Constraint associate a questa colonna
     * @return ConstraintInterface
     */
    public function getConstraints();

    /**
     * Aggiunge una constraint alla colonna
     * @param ConstraintInterface $constraint
     */
    public function addConstraint(ConstraintInterface $constraint);
    
    /**
     * Restituisce la classe Constraint di default
     * @return Constraint\DefaultConstraintInterface
     */
    public function getDefault();
    
    /**
     * Restituite true o false se la colonna ha un valore di default
     * @return bool
     */
    public function hasDefault();
    
    /**
     * Restituisce true o false se la colonna ha un indice associato
     * @return bool
     */
    public function hasIndex();
    
    /**
     * Restituisce la lista degli indici associati alla colonna
     * @return Constraint\IndexConstraintInterface[]
     */
    public function getIndex();
    
    /**
     * Restituisce true o false se esiste una constraint di tipo check
     * @return bool
     */
    public function hasCheck();
    
    /**
     * Restituisce le constraint di tipo check
     * @return Constraint\CheckConstraintInterface[]
     */
    public function getCheck();
    
    /**
     * Restituisce true o false se esiste una foreign key sulla colonna
     * @return bool
     */
    public function hasForeignKey();
    
    
    /**
     * Restituisce le foreign key della colonna
     * @return Constraint\ForeignKeyConstraintInterface
     */
    public function getForeignKey();

}
