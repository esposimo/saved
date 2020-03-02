<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog;

/**
 * L'interfaccia PrintableInterface rende stampabile un'oggetto di catalogo. <br>
 * A differenza del nome, il parametro toString() può essere usato per avere il nome <br>
 * dell'oggetto di catalogo in altre forme. Ad esempio se l'oggetto di catalogo è una <br>
 * tabella e si usa il metodo CatalogObjectInterface::getName() , verrà restituito il <br>
 * nome della tabella. Usando toString() verrà restituito il nome sotto forma di <br>
 * schema.table
 * @author A760526
 */
interface PrintableInterface {

    public function toString();
}