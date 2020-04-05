<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog;
use smn\lazyc\dbc\Catalog\CatalogObjectInterface;
use smn\lazyc\dbc\Catalog\TableInterface;
/**
 * L'interfaccia SchemaInterface rappresenta uno Schema generico di una database<br>
 * Estendendo la CatalogObjectInterface, se si vuole implementare una classe Schema<br>
 * da 0 bisognerà implementare anche il modo in cui sono gestiti i metodi di <br>
 * CatalogObjectInterface
 * @author A760526
 */

interface SchemaInterface extends CatalogObjectInterface {

    /**
     * Aggiunge una $table allo schema.
     * @param TableInterface $table Tabella figlio da aggiungere allo schema
     */
    public function addTable(TableInterface $table);

    /**
     * Rimuove la tabella di nome $table
     * @param string $table Nome della tabella
     */
    public function removeTable(string $table);

    /**
     * Restituisce true/false se la tabella di nome $table esiste
     * @param string $table Nome della tabella
     */
    public function hasTable(string $table);

    /**
     * Restituisce una TableInterface se la tabella di nome $table esiste
     * @param string $table Nome della tabella
     * @return TableInterface
     */
    public function getTable(string $table);

    /**
     * Restituisce un array con tutte le tabelle dello schema
     * @return TableInterface[]
     */
    public function getAllTables();

}
