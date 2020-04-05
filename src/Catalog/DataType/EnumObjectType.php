<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;

use smn\lazyc\dbc\Catalog\DataType\ColumnObjectType;
use smn\lazyc\dbc\Catalog\DataType\EnumObjectTypeInterface;
use \PDO;

/**
 * Description of EnumObjectType
 *
 * @author A760526
 */

class EnumObjectType extends ColumnObjectType implements EnumObjectTypeInterface {
    
    protected $type = PDO::PARAM_STR;
    
    public function isValid($value) {
        if (!is_array($value)) {
            return false;
        }
        $map = array_map(function($value) {
            $type = gettype($value);
            
            if ($type == 'object') {
                return get_class($type);
            }
            if ($type == 'resource') {
                return get_resource_type($type);
            }
            return gettype($value);
        });
        $types = array_flip($map);
        return (corun($types) == 1) ? true : false;
    }
}


