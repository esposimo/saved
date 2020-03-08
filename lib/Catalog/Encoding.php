<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog;
use smn\lazyc\dbc\Catalog\EncodingInterface;

/**
 * Description of Encoding
 *
 * @author A760526
 */
class Encoding implements EncodingInterface {
    
    /**
     * Nome del charset
     * @var string
     */
    protected $charset;
    
    /**
     *  Costruttore
     * @param string $charset
     */
    public function __construct(string $charset) {
        $this->charset = $charset;
    }

    /**
     * Converte un valore considerandolo come stringa di tipo charset della classe
     * in charset di Encoding
     * @param \EncodingInterface $encoding
     * @param string $value
     * @return type
     */
    public function convert(EncodingInterface $encoding, string $value) {
        $in = $this->getCharSet();
        $out = $encoding->getCharSet();
        if (($result = iconv($in, $out, $value)) !== false) {
            return $result;
        }
        // throw

    }

    /**
     * Restituisce il nome del charset
     * @return string
     */
    public function getCharSet() {
        return $this->charset;
    }

}

