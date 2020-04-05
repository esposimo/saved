<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;
use \smn\lazyc\dbc\Catalog\DataType\StringObjectTypeInterface;
use \smn\lazyc\dbc\Catalog\DataType\ColumnObjectType;
use \PDO;

/**
 * Description of StringObjectType
 *
 * @author A760526
 */
class StringObjectType extends ColumnObjectType implements StringObjectTypeInterface {

    protected $type = PDO::PARAM_STR;

    public function isValid($value) {
        return is_string($value);
    }

    public function countBytes() {
        
    }

    public function countChar() {
        
    }

}
