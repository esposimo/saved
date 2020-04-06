<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog;

/**
 *
 * @author A760526
 */
interface EncodingInterface {
    
    /**
     * Restituisce il nome del charset
     * @return String
     */
    public function getCharSet();
    
    /**
     * Converte una stringa $value in $encoding
     * @param EncodingInterface $encoding
     * @param String $value
     */
    public function convert(EncodingInterface $encoding, String $value);


    public static function createEncodingInstanceFromName(string $name = '');
    
}