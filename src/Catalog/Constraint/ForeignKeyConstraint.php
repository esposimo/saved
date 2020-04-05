<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\Constraint;
use smn\lazyc\dbc\Catalog\ColumnInterface;
use smn\lazyc\dbc\Catalog\TableInterface;

/**
 * Description of ForeignKeyConstraint
 *
 * @author A760526
 */
class ForeignKeyConstraint extends Constraint implements ForeignKeyConstraintInterface {

    /**
     * Variabile che definisce il tipo di Constraint della classe
     * @var type 
     */
    protected $type = self::CONSTRAINT_FK;

    /**
     * Colonna esterna alla quale fa riferimento la ForeignKey
     * @var ColumnInterface 
     */
    protected $references = null;

    /**
     * Configura la colonna alla quale fa riferimento la Foreign Key
     * @param ColumnInterface $column
     */
    public function referencesTo(ColumnInterface $column) {
        $this->references = $column;
        // check se $column è una primary key nella sua tabella ?
    }

    /**
     * 
     * @return TableInterface
     */
    public function getTableReference() {
        if ($this->references === null) {
            return false;
        }
        return $this->references->getTable();
    }
    
    /**
     * Crea una constraint class di tipo Foreign Key
     * @param ColumnInterface $columns Lista delle colonne associate a questa constraint
     * @param Array $options Opzioni estese per constraint custom
     * @return static::class
     */
    public static function createConstraintInstance(array $columns, $options = []) {
        $instance = parent::createConstraintInstance($columns, $options);
        if (array_key_exists('reference', $options)) {
            $instance->relationTo($options['reference']);
        }
        return $instance;
    }

}

