<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Helper;
use smn\lazyc\dbc\Helper\ExtendedPropertiesInterface;

/**
 * Description of ExtendedProperties
 *
 * @author A760526
 */
class ExtendedProperties implements ExtendedPropertiesInterface {

    /**
     * Array con i valori
     * @var Array 
     */
    protected $extended_properties = [];

    /**
     * Se true permette la sovrascrittura delle property esistenti
     * @var bool 
     */
    public $allowOverride = true;

    /**
     * Costruttore della classe
     * @param type $properties
     * @param type $allowOverride
     */
    public function __construct($properties = [], $allowOverride = true) {
        foreach ($properties as $name => $value) {
            $this->setProperty($name, $value);
        }
        $this->allowOverride = $allowOverride;
    }

    /**
     * Restituisce una property. Se non esiste, restituisce null
     * @param string $name
     * @return Mixed
     */
    public function getProperty(string $name) {
        return ($this->hasProperty($name)) ? $this->extended_properties[$name] : null;
    }

    /**
     * Configura una property con nome $name e valore $value
     * @param string $name
     * @param Mixed $value
     */
    public function setProperty(string $name, $value) {
        if ((($this->hasProperty($name)) && ($this->allowOverride)) || (!$this->hasProperty($name))) {
            $this->extended_properties[$name] = $value;
        }
    }

    /**
     * Restituisce true o false se una property $name esiste
     * @param string $name
     * @return bool
     */
    public function hasProperty(string $name) {
        return array_key_exists($name, $this->extended_properties);
    }

    /**
     * Restituisce tutte le properties
     * @return Array
     */
    public function getProperties() {
        return $this->extended_properties;
    }

}
