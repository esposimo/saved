<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;

use \smn\lazyc\dbc\Catalog\DataType\NumericObjectType;

/**
 * Description of SignedInt
 *
 * @author A760526
 */
class SignedInt extends NumericObjectType {
    const INTEGER = true;
    
    const SIGNED = true;
    
    
    /**
     * Definisce il valore minimo del tipo di oggetto
     * @type int
     */
    protected $minValue = -2147483648;
    
    /**
     * Definisce il valore massimo del tipo di oggetto
     * @type int
     */
    protected $maxValue = 2147483647;
}