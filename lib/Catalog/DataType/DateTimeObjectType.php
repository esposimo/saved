<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\DataType;

use \smn\lazyc\dbc\Catalog\DataType\DateTimeObjectTypeInterface;
use \smn\lazyc\dbc\Catalog\DataType\ColumnObjectType;
use \PDO;
/**
 * Description of DateTimeObjectType
 *
 * @author A760526
 */

class DateTimeObjectType extends ColumnObjectType implements DateTimeObjectTypeInterface {
    
    
    protected $type = PDO::PARAM_STR;
    
    
    protected $year;
    
    protected $month;
    
    protected $day;
    
    
    protected $hour;
    
    protected $minutes;
    
    protected $seconds;
    
    protected $microseconds;
    
    public function year($year) {
        $this->year = $year;
    }
    
    public function month($month) {
        $this->month = $month;
    }

    public function day($day) {
        $this->day = $day;
    }

    public function hour($hour) {
        $this->hour = $hour;
    }

    public function microsecond($msec) {
        $this->microseconds = $msec;
    }

    public function minute($minute) {
        $this->minutes = $minute;
    }


    public function second($second) {
        $this->second = $second;
    }

    public function isValid($value) {
        return true;
    }

    public function getDay() {
        return $this->day;
    }

    public function getHour() {
        return $this->hour;
    }

    public function getMicroSecond() {
        return $this->microseconds;
    }

    public function getMinute() {
        return $this->minutes;
    }

    public function getMonth() {
        return $this->month;
    }

    public function getSecond() {
        return $this->seconds;
    }

    public function getYear() {
        return $this->year;
    }

}
