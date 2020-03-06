<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;

use \smn\lazyc\dbc\Catalog\DataType\ColumnObjectTypeInterface;
/**
 *
 * @author A760526
 */
interface DateTimeObjectTypeInterface extends ColumnObjectTypeInterface {
    
    public function year($year);
    public function month($month);
    public function day($day);
    
    public function hour($hour);
    public function minute($minute);
    public function second($second);
    public function microsecond($msec);
    
    public function getYear();
    public function getMonth();
    public function getDay();
    
    public function getHour();
    public function getMinute();
    public function getSecond();
    public function getMicroSecond();
    
}

