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
interface StringObjectTypeInterface extends ColumnObjectTypeInterface {

    /**
     * Restituisce il numero di caratteri della stringa
     * @return int
     */
    public function countChar();

    /**
     * Restituisce il numero di bytes utili per storicizzare la stringa
     * @return int
     */
    public function countBytes();
}
