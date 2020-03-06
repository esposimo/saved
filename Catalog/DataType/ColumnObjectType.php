<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace smn\lazyc\dbc\Catalog\DataType;
use \smn\lazyc\dbc\Catalog\ColumnInterface;
use \PDO;
/**
 * Description of ColumnObjectType
 *
 * @author A760526
 */
abstract class ColumnObjectType implements ColumnObjectTypeInterface {

    /**
     * levare il riferimento alla colonna. forse.
     */
    const COLUMN_TYPE_NUMERIC = 1; // variabili numeriche
    const COLUMN_TYPE_DATE = 2; // variabili di tipo date and time
    const COLUMN_TYPE_CHAR = 3; // variabili di tipo char (ovvero storicizzano i caratteri in base ai byte)
    const COLUMN_TYPE_UNICODE = 4; // variaibli di tipo unicode (storicizzano gli unicode)
    const COLUMN_TYPE_BINARY = 5;
    const COLUMN_TYPE_MISC = 6;

    protected $dataTypeName = [
        self::COLUMN_TYPE_NUMERIC => 'numeric',
        self::COLUMN_TYPE_DATE => 'datetime',
        self::COLUMN_TYPE_CHAR => 'string',
        self::COLUMN_TYPE_UNICODE => 'unicode',
        self::COLUMN_TYPE_BINARY => 'binary',
        self::COLUMN_TYPE_MISC => 'misc'
    ];

    /**
     *
     * @var int 
     */
    protected $type;
    protected $constbind = null;

    /**
     *
     * @var \smn\lazyc\dbc\Catalog\ColumnInterface 
     */
    protected $column;

    public function __construct(ColumnInterface $column) {
        $this->column = $column;
    }

    public function getType() {
        return $this->type;
    }

    /**
     * Restituisce il tipo di PDO::PARAM_* da utilizzare per il binding
     * @param type $value
     * @return type
     */
    public function checkTypeColumn($value) {
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
        $this->constbind = $param_type;
        return $param_type;
    }

    abstract public function isValid($value);

    public function getBindType() {
        return $this->constbind;
    }

}