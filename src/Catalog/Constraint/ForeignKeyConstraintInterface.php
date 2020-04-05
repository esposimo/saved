<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog\Constraint;
use smn\lazyc\dbc\Catalog\TableInterface;
use smn\lazyc\dbc\Catalog\ColumnInterface;
/**
 *
 * @author A760526
 */
interface ForeignKeyConstraintInterface extends ConstraintInterface {

    /**
     * Aggiunge la colonna esterna alla quale fa riferimento la Foreign Key
     * @param ColumnInterface $column
     */
    public function referencesTo(ColumnInterface $column);

    /**
     * Tramite la colonna configurata con ForeignKeyConstraintInterface::referencesTo() <br>
     * restituisce la tabella esterna alla quale punta la tabella.
     * @return TableInterface
     */
    public function getTableReference();
}
