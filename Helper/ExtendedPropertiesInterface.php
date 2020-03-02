<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Helper;

/**
 *
 * @author A760526
 */
interface ExtendedPropertiesInterface {

    /**
     * Configura una proprietà alla classe
     * @param string $name
     * @param type $value
     */
    public function setProperty(string $name, $value);

    /**
     * Restituisce la proprietà con nome $name
     * @param string $name
     * @return Mixed
     */
    public function getProperty(string $name);
}