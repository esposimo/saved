<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Helper;

/**
 * Description of PlaceHolderSystem
 *
 * @author A760526
 */
class PlaceHolderSystem {

    /**
     * Pattern da utilizzare. I placeholders vengono racchiusi tra {}
     * @var String 
     */
    protected $pattern;

    /**
     * Lista dei placeholders. Il valore di un placeholder può essere il valore stesso
     * oppure una callback. Alla callback viene passato sempre un solo valore, che è l'istanza
     * PlaceHolderSystem stessa
     * @var Array
     */
    protected $placeholders = [];

    /**
     * Array di coppie chiave=>valore che possono essere utilizzate nelle callback
     * Inserendo dei parametri tramite i metodi setParam e getParam, essi possono poi
     * essere richiamati in un secondo momento nelle callback assegnate ai placeholders.
     * Questo perchè le callback ricevono come unico parametro l'istanza PlaceHolderSystem stessa
     * ed è quindi possibile ricavarsi all'interno di ogni callback i vari parametri
     * @var Array
     */
    protected $params = [];

    /**
     * Configura il pattern dell'istanza
     * @param String $pattern
     */
    public function setPattern($pattern) {
        $this->pattern = $pattern;
    }

    /**
     * Restituisce il pattern
     * @return String
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * Aggiunge un placeholder $name con valore $value. Se il placeholder $name
     * già esiste viene sostituito.
     * @param String $name
     * @param String|callback $value
     */
    public function setPlaceHolder($name, $value) {
        $this->placeholders[$name] = $value;
    }

    /**
     * Configura un parametro da essere poi richiamato nelle callback. Se il parametro
     * già esiste viene sostituito
     * @param string $name
     * @param Mixed $value
     */
    public function setParam(string $name, $value) {
        $this->params[$name] = $value;
    }

    /**
     * Restituisce il parametro $name. Se non esiste restituisce null
     * @param string $name
     * @return bool|mixed
     */
    public function getParam(string $name) {
        return (array_key_exists($name, $this->params)) ? $this->params[$name] : null;
    }

    /**
     * Restituisce il placeholder $name. Se non esiste restituisce null
     * @param type $name
     * @return type
     */
    public function getPlaceHolder($name) {
        return (array_key_exists($name, $this->placeholders)) ? $this->placeholders[$name] : null;
    }

    /**
     * Renderizza la stringa in base al pattern e ai placeeholders
     * Ricordati il link dove hai preso spunto
     * @return String
     */
    private function vksprintf() {
        $map = $this->placeholders;
        $string = $this->pattern;
        $patter_regex = '/{([A-Za-z0-9\.\:_]+)+}/';
        $return = preg_replace_callback($patter_regex,
                function($p) use ($map) {
            $positions = array_flip(array_keys($map));
            $matched = $p[1];
            if (array_key_exists($matched, $positions)) {
                $key = $positions[$matched];
                $key++;
                if (is_callable($map[$matched])) {
//                    $pms = (array_key_exists($matched, $this->params)) ? $this->params[$matched] : [];
                    return call_user_func_array($map[$matched], [$this]);
                }
                return sprintf('%%%s$s', $key);
            }
// se non c'è un placeholder configurato lascia il placeholder
            return $p[0];
        }
                , $string);
        return vsprintf($return, $map);
    }

    /**
     * 
     * @return String
     */
    public function render() {
        return $this->vksprintf();
    }

}