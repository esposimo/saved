<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Catalog;

use smn\lazyc\dbc\Catalog\CatalogObjectInterface;
use smn\lazyc\dbc\Catalog\EncodingInterface;

/**
 * Description of CatalogObject
 *
 * @author A760526
 */
class CatalogObject implements CatalogObjectInterface {

    const CATALOG_OBJECT_SCHEMA = 'schema';
    const CATALOG_OBJECT_TABLE = 'table';
    const CATALOG_OBJECT_COLUMN = 'column';

    /**
     * Nome dell'oggetto di catalogo
     * @var String 
     */
    protected $name;

    /**
     * Tipologia oggetto di catalogo
     * @var String 
     */
    protected $type;

    /**
     * Padre dell'oggetto di catalogo
     * @var type 
     */
    protected $parent = null;

    /**
     * Lista dei figli dell'oggetto di catalogo
     * @var CatalogObjectInterface[][]
     */
    protected $child = [];
    
    /**
     *
     * @var EncodingInterface 
     */
    protected $encoding;
    
    /**
     * Costruttore della classe. Crea un oggetto di catalogo con il nome $name <br>
     * e di tipo $type.
     * @param string $name Nome dell'oggetto di catalogo
     * @param string $type tipologia oggetto di catalogo
     */
    public function __construct(string $name, string $type) {
        $this->setName($name);
        $this->setType($type);
    }

    /**
     * Restituisce il nome dell'oggetto di catalogo
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Restituisce il tipo di oggetto di catalogo
     * @return String
     */
    public function getType() {
        return $this->type;
    }

    /**
     * Configura nome dell'oggetto di catalogo
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * Configura tipologia oggetto di catalogo
     * @param string $type
     */
    public function setType(string $type) {
        $this->type = $type;
    }

    /**
     * Aggiunge come figlio un oggetto di catalogo. Se l'oggetto di catalogo <br>
     * è già presente, non sarà aggiunto.
     * @param CatalogObjectInterface $object
     */
    public function addChild(CatalogObjectInterface $object) {
        if ($this->hasChildByInstance($object)) {
            return; // throw exception ?
        }
        $type = $object->getType();
        $this->child[$type][] = $object;
        $object->setParent($this);
    }

    /**
     * Restituisce un figlio di nome $name e tipo $type
     * @param string $name
     * @param string $type
     * @return CatalogObjectInterface
     */
    public function getChild(string $name, string $type) {
        if (($index = $this->getIndexOfInstance($name, $type)) !== false) {
            return $this->child[$type][$index];
        }
        return null;
    }

    /**
     * Restituisce la posizione nell'array dei figli relativa all'oggetto di <br>
     * catalogo avente nome $name e tipo $type
     * @param string $name
     * @param string $type
     * @return boolean
     */
    private function getIndexOfInstance(string $name, string $type) {
        if (!$this->hasType($type)) {
            return false;
        }
        foreach ($this->child[$type] as $index => $child) {
            if (($child->getName() == $name) && ($child->getType() == $type)) {
                return $index;
            }
        }
        return false;
    }

    /**
     * Restituisce i figli dell'oggetto di catalogo. Indicando $type si può <br>
     * filtrare per tipo la lista dei figli. Se non esistono oggetti di tipo $type <br>
     * verrà restituito un array vuoto
     * @param string $type
     * @return array
     */
    public function getChildren(string $type = null) {
        if (is_null($type)) {
            $children = [];
            // se non richiedo un determinato tipo, creo un array con tutti i figli
            // di ogni tipo
            // se non esistono figli, sarà restituito $children con il suo primo valore
            // ovvero array vuoto
            foreach ($this->child as $type) {
                $children = array_merge($children, $type);
            }
            return $children;
        }
        // if $type non è null i risultati possono essere 2
        // 1. Array vuoto perchè non ci sono oggetti di tipo $type
        // 2. Array con quel tipo di oggetti
        // E' importante restituire sempre un array. Se vuoto vuol dire che non ci sono figli
        // (di ogni tipo o specifico)
        return (!$this->hasType($type)) ? [] : $this->child[$type];
    }

    /**
     * Restituisce il padre dell'oggetto di catalogo
     * @return CatalogObjectInterface
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Restituisce true/false se l'oggetto di catalogo con $name e di tipo $type <br>
     * è uno dei figli dell'istanza
     * @param string $name
     * @param string $type
     * @return bool
     */
    public function hasChild(string $name, string $type) {
        if (!$this->hasType($type)) {
            return false;
        }
        $children = array_map(function(CatalogObjectInterface $child) {
            return $child->getName();
        }, $this->child[$type]);
        
        return (array_search($name, $children) === false) ? false : true;
        
    }

    /**
     * Restituisce true/false se l'oggetto di catalogo ha un figlio $object;
     * @param CatalogObjectInterface $object
     * @return bool
     */
    public function hasChildByInstance(CatalogObjectInterface $object) {
        $type = $object->getType();
        if (!$this->hasType($type)) {
            return false;
        }
        foreach ($this->child[$type] as $child) {
            if ($child === $object) {
                return true;
            }
        }
        return false;
    }

    /**
     * Restituisce true/false se tra i figli esiste almeno un oggetto di catalogo <br>
     * di tipo $type
     * @param string $type
     * @return bool
     */
    public function hasType(string $type) {
        return array_key_exists($type, $this->child);
    }

    /**
     * Rimuove il figlio di nome $name e tipologia $type
     * @param string $name
     * @param string $type
     */
    public function removeChild(string $name, string $type) {
        if (($index = $this->getIndexOfInstance($name, $type)) !== false) {
            unset($this->child[$type][$index]);
            $this->child[$type] = array_values($this->child[$type]);
        }
    }

    /**
     * Rimuove l'istanza figlio $object.
     * @param CatalogObjectInterface $object
     */
    public function removeChildByInstance(CatalogObjectInterface $object) {
        if (($index = $this->getIndexOfInstance($object->getName(), $object->getType())) !== false) {
            unset($this->child[$type][$index]);
            $this->child[$type] = array_values($this->child[$type]);
        }
    }

    /**
     * Configura l'oggetto di catalogo padre di questo.
     * @param CatalogObjectInterface $object
     */
    public function setParent(CatalogObjectInterface $object) {
        if ($this->getParent() === $object) {
            return; // ho già questo padre
        }
        $this->parent = $object;
        $object->addChild($this);
    }

    /**
     * Restituisce l'istanza Encoding
     * @return EncodingInterface
     */
    public function getEncoding() {
        return $this->encoding;
    }

    /**
     * Configura l'istanza Encoding
     * @param EncodingInterface $encoding
     */
    public function setEncoding(EncodingInterface $encoding) {
        $this->encoding = $encoding;
    }

}
