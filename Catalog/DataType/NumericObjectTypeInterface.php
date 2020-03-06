<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;

interface NumericObjectTypeInterface extends ColumnObjectTypeInterface {
    
    public function isSigned();
    
    public function isUnsigned();
    
    public function minValue();
    
    public function maxValue();
    
    public function isInteger();
    
    public function isDecimal();
        
}