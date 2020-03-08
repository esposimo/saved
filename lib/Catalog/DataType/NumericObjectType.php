<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;

use \smn\lazyc\dbc\Catalog\DataType\NumericObjectTypeInterface;
use \smn\lazyc\dbc\Catalog\DataType\ColumnObjectType;
use \PDO;

/**
 * Description of NumericObjectType
 *
 * @author A760526
 */
class NumericObjectType extends ColumnObjectType implements NumericObjectTypeInterface {
    
    /**
     * Definisce la costante per bindare i parametri
     * @var int 
     */
    protected $type = PDO::PARAM_INT;
    
    /**
     * Definisce se il tipo di numero è intero o con virgola.
     * @type bool
     */
    const INTEGER = true;
    
    /**
     * Definisce se il tipo di numero è con segno o senza
     * @type bool
     */
    const SIGNED = false;
    
    
    /**
     * Definisce il valore minimo del tipo di oggetto
     * @type int
     */
    protected $minValue = 0;
    
    /**
     * Definisce il valore massimo del tipo di oggetto
     * @type int
     */
    protected $maxValue = 0xffffffff;
    
    
    
    public function isDecimal() {
        return !self::INTEGER;
    }

    public function isInteger() {
        return self::INTEGER;
    }

    public function isSigned() {
        return self::SIGNED;
    }

    public function isUnsigned() {
        return !self::SIGNED;
    }

    public function isValid($value) {
        if (($value >= $this->minValue()) && ($value <= $this->minValue())) {
            return true;
        }
        return false;
    }

    public function maxValue() {
        return $this->maxValue;
    }

    public function minValue() {
        return $this->minValue;
    }

}
