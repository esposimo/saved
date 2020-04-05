<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace smn\lazyc\dbc\Catalog\Constraint;
use smn\lazyc\dbc\Catalog\ColumnInterface;
/**
 * L'interfaccia Constraint rappresenta una Constraint di un database
 * @author A760526
 */
interface ConstraintInterface {

    /**
     * Definisce il nome della Constraint
     * @param type $name
     */
    public function setName($name);

    /**
     * Restituisce il nome della constraint
     * @return string
     */
    public function getName();

    /**
     * Definisce la tipologia di Constraint
     * @param type $type
     */
    public function setType($type);

    /**
     * Restituisce il tipo di constraint
     */
    public function getType();

    /**
     * Relaziona la Constraint ad una Colonna
     * @param ColumnInterface $column
     */
    public function relationTo(ColumnInterface $column);

    /**
     * Restituisce true o false se la Constraint è relazionata alla colonna $column
     * @param string $column
     */
    public function isRelatedTo(string $column);

    /**
     * Rimuove la relazione tra constraint e la colonna $column
     * @param string $column
     */
    public function removeRelationTo(string $column);

    /**
     * Restituisce tutte le colonne relazionate alla Constraint
     * @return ColumnInterface[]
     */
    public function getRelations();

    /**
     * Restituisce, tramite una delle colonna relazionate alla Constraint, la tabella <br>
     * alla quale appartiene la constraint
     * @return TableInterface
     */
    public function getTable();
    
    /**
     * Crea una constraint class
     * @param array $columns Lista delle colonne associate a questa constraint
     * @param array $options Opzioni estese per constraint custom name => Nome della constraint
     * @return static::class
     */
    public static function createConstraintInstance(array $columns, $options = []);
    
 
}
