<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog;


/**
 * L'interfaccia CatalogObjectInterface definisce la struttura necessaria per<br>
 * rappresentare un oggetto di catalogo. Un oggetto di catalogo ha un nome, un tipo<br>
 * (schema, tabella, colonna, etc) e una struttura che ne definisce padre e figli
 * @author A760526
 */
interface CatalogObjectInterface {

    /**
     * Restituisce il nome dell'oggetto di catalogo
     * @return string
     */
    public function getName();

    /**
     * Restituisce il tipo di oggetto di catalogo (schema, tabella, etc)
     * @return string
     */
    public function getType();

    /**
     * Aggiunge un figlio a questo oggetto di catalogo. Anche il figlio<br>
     * deve essere un oggetto di catalogo
     * @param CatalogObjectInterface $object
     */
    public function addChild(CatalogObjectInterface $object);

    /**
     * Restituisce l'oggetto di catalogo figlio avente nome $name e tipologia $type
     * @param string $name
     * @param string $type
     * @return CatalogObjectInterface
     */
    public function getChild(string $name, string $type);

    /**
     * Restituisce true/false se l'oggetto di catalogo con nome $name e tipologia<br>
     * $type esiste
     * @param string $name
     * @param string $type
     * @return bool
     */
    public function hasChild(string $name, string $type);

    /**
     * Restituisce true/false se l'oggetto di catalogo ha almeno un figlio di<br>
     * tipo $type
     * @param string $type
     * @return bool
     */
    public function hasType(string $type);

    /**
     * Restituisce true o false se l'oggetto di catalogo $object è un figlio
     * @param CatalogObjectInterface $object
     * @return bool
     */
    public function hasChildByInstance(CatalogObjectInterface $object);

    /**
     * Restituisce tutti i figli dell'oggetto di catalogo. Se si indica il tipo<br>
     * verranno restituiti solo i figli di tipologia $type. Se non esistono figli<br>
     * di tipo $type verrà restituito un array vuoto
     * @param string $type
     * @return array
     */
    public function getChildren(string $type = null);

    /**
     * Rimuove un figlio , se presente, avente nome $name e di tipologia $type.
     * @param string $name
     * @param string $type
     */
    public function removeChild(string $name, string $type);

    /**
     * Rimuove il figlio $object se presente
     * @param CatalogObjectInterface $object
     */
    public function removeChildByInstance(CatalogObjectInterface $object);

    /**
     * Configura $object come padre dell'oggetto di catalogo
     * @param CatalogObjectInterface $object
     */
    public function setParent(CatalogObjectInterface $object);

    /**
     * Restituisce il padre dell'oggetto di catalogo. Se non esiste un padre<br>
     * restituisce null;
     * @return CatalogObjectInterface
     */
    public function getParent();

    /**
     * Rimuove il padre dell'oggetto di catalogo.
     */
    public function removeParent();
    
    /**
     * Restituisce l'istanza che definisce il charset di questo oggetto di catalogo
     * @return EncodingInterface
     */
    public function getEncoding();
    
    /**
     * Configura l'istanza che definisce il charset di questo oggetto di catalogo
     * @param EncodingInterface $encoding
     */
    public function setEncoding(EncodingInterface $encoding);
    
    /**
     * Crea un oggetto di catalogo in base a ciò che viene indicato
     * @param string $name Nome oggetto di catalogo
     * @param array $options Opzioni estese per oggetti custom
     * @return static::class
     */
    public static function createCatalogObjectInstance(string $name, array $options = []);
    
}

