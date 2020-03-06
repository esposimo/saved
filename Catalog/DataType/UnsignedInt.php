<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;

/**
 * Description of UnsignedInt
 *
 * @author A760526
 */
class UnsignedInt extends NumericObjectType {
    
    
    protected $minValue = 0;
    
    protected $maxValue = 4294967295;
    
}
