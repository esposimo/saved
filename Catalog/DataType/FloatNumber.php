<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;

use \smn\lazyc\dbc\Catalog\ColumnInterface;
use \smn\lazyc\dbc\Catalog\DataType\NumericObjectType;
use \PDO;
/**
 * Description of FloatNumber
 *
 * @author A760526
 */
class FloatNumber extends NumericObjectType {
    
    
    /**
     * Usiamo PARAM_STR perchè non esistono PDO::PARAM_* per numeri con la virgola
     * @type int
     */    
     protected $type = PDO::PARAM_STR;
    
    /**
     * Definisce se il tipo di numero è intero o con virgola.
     * @type bool
     */
    const INTEGER = false;
    
    /**
     * Definisce se il tipo di numero è con segno o senza
     * @type bool
     */
    const SIGNED = false;
    
    
    /**
     * Definisce il valore minimo del tipo di oggetto
     * @type int
     */
    protected $minValue = 0.0;
    
    /**
     * Definisce il valore massimo del tipo di oggetto
     * @type int
     */
    protected $maxValue = 0.0;
    
    
    public function __construct(ColumnInterface $column) {
        parent::__construct($column);
        $this->minValue = PHP_FLOAT_MIN;
        $this->maxValue = PHP_FLOAT_MAX;
    }
    public function isValid($value) {
       return is_float((float) $value);
    }    
}
