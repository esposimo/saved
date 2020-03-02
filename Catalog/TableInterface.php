<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog;

use smn\lazyc\dbc\Catalog\SchemaInterface;
use smn\lazyc\dbc\Catalog\ColumnInterface;

/**
 * La TableInterface rappresenta un oggetto di catalogo di tipo Tabella.
 * @author A760526
 */
interface TableInterface extends CatalogObjectInterface {

    /**
     * Configura come padre lo schema $schema
     * @param SchemaInterface $schema Istanza schema
     */
    public function setSchema(SchemaInterface $schema);

    /**
     * Restituisce l'istanza Schema che è padre della tabella
     * @return SchemaInterface
     */
    public function getSchema();

    /**
     * Aggiunge una colonna alla tabella.
     * @param ColumnInterface $column Istanza rappresentante la colonna
     */
    public function addColumn(ColumnInterface $column);

    /**
     * Rimuove la colonna con nome $column da quelle associate alla tabella
     * @param string $column Nome della colonna
     */
    public function removeColumn(string $column);

    /**
     * Restituisce true/false se la colonna con nome $column esiste
     * @param string $column Nome della colonna
     */
    public function hasColumn(string $column);

    /**
     * Restituisce un'istanza di tipo ColumnInterface se la colonna di nome <br>
     * $column esiste
     * @param string $column Nome della colonna
     * @return ColumnInterface
     */
    public function getColumn(string $column);

    /**
     * Restituisce tutte le colonne appartenenti alla tabella
     * @return ColumnInterface[]
     */
    public function getAllColumns();

    /**
     * Restituisce l'istanza PrimaryKeyConstraint che rappresenta la Primary Key<br>
     * di questa tabella
     * @return PrimaryKeyConstraintInterface
     */
    public function getPrimaryKey();
    
}
