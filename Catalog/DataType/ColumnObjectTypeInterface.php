<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace smn\lazyc\dbc\Catalog\DataType;
/**
 *
 * @author A760526
 */
interface ColumnObjectTypeInterface {

    public function getType();

    public function isValid($value);

    public function getBindType();
}
