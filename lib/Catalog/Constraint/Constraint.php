<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\Constraint;
use smn\lazyc\dbc\Catalog\Constraint\ConstraintInterface;
use smn\lazyc\dbc\Catalog\ColumnInterface;

/**
 * La classe Constraint rappresenta una Constraint presente in una tabella
 *
 * @author A760526
 */


class Constraint implements ConstraintInterface {

    /**
     * Lista delle constraint che rappresentano vari tipologie di Constraint
     */
    const CONSTRAINT_INDEX = 'ctype_index';
    const CONSTRAINT_PK = 'ctype_primary_key';
    const CONSTRAINT_FK = 'ctype_foreign_key';
    const CONSTRAINT_NOT_NULL = 'ctype_not_null';
    const CONSTRAINT_UNIQUE = 'ctype_unique';
    const CONSTRAINT_CHECK = 'ctype_check';
    const CONSTRAINT_DEFAULT = 'ctype_default';

    /**
     * Nome della Constraint
     * @var string 
     */
    protected $name;
    
    /**
     * Tipologia di constraint
     * @var string 
     */
    protected $type;
    
    /**
     * Lista delle colonne relazionate alla Constraint
     * @var ColumnInterface[] 
     */
    protected $relations = [];

    /**
     * Aggiunge le colonne relazionate a questa primary key
     * @param ColumnInterface[] $columns
     */
    public function __construct(array $columns = []) {
        foreach ($columns as $column) {
            if ($column instanceof ColumnInterface) {
                $this->relationTo($column);
            }
        }
    }

    /**
     * Restituisce il nome della Constraint
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Restituisce le colonne relazionate alla primary key
     * @return ColumnInterface[]
     */
    public function getRelations() {
        return $this->relations;
    }

    /**
     * Restituisce il tipo di constraint
     * @return type
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Restituisce true o false se la colonna $column è parte constraint
     * @param string $column
     * @return boolean
     */
    public function isRelatedTo(string $column) {
        foreach ($this->relations as $rel) {
            if ($rel->getName() === $column) {
                return true;
            }
        }
        return false;
    }

    /**
     * Associa la colonna alla constraint
     * @param ColumnInterface $column
     * @return type
     */
    public function relationTo(ColumnInterface $column) {
        // se la relazione già esiste, non la riaggiungo
        if (array_search($column, $this->relations, true) !== false) {
            return;
        }
        $this->relations[] = $column;
        // dico alla colonna di aggiungere me stesso
        $column->addConstraint($this);
    }

    /**
     * Rimuove una colonna dalla relazione con la constraint
     * @param string $column
     */
    public function removeRelationTo(string $column) {
        foreach ($this->relations as $i => $rel) {
            if ($rel->getName() === $column) {
                unset($this->relations[$i]);
            }
        }
        $this->relations = array_values($this->relations); // resetto gli indici
    }

    /**
     * Definisce il nome della Constraint
     * @param type $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * Definisce il tipo di constraint
     * @param type $type
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * Restituisce la tabella alla quale è relazionata la constraint. <br>
     * La constraint fa riferimento ad una colonna, quindi se la constraint <br>
     * è stata associata ad una colonna è possibile sapere di conseguenza la tabella
     * @return TableInterface
     */
    public function getTable() {
        if (count($this->relations) > 0) {
            $column = $this->relations[0];
            return $column->getTable();
        }
        return false;
    }

    /**
     * Crea una constraint class
     * @param ColumnInterface $columns Lista delle colonne associate a questa constraint
     * @param Mixed $args Opzioni estese per constraint custom
     * @return static::class
     */
    public static function createConstraintInstance(array $columns, $options = []) {
         if (self::class == static::class) {
            return false;
        }
        $instance = new static($columns);
        if (array_key_exists('name', $options)) {
            $instance->setName($options['name']);
        }
        return $instance;
    }
}
