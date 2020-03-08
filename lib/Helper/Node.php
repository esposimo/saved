<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace smn\lazyc\dbc\Helper;
use smn\lazyc\dbc\Helper\ExtendedPropertiesInterface;
/**
 * Description of Node
 *
 * @author A760526
 */
class Node implements ExtendedPropertiesInterface {

    /**
     * Contiene l'oggetto che fa parte della struttura ad albero
     * @var Mixed 
     */
    protected $value;

    /**
     * Istanza node che rappresenta il padre dell'istanza
     * @var Node 
     */
    protected $parent = null;

    /**
     * Lista dei nodi figli di questa istanza
     * @var Node[] 
     */
    protected $child = [];

    /**
     *
     * @var ExtendedProperties 
     */
    protected $extended_properties;

    /**
     * Costruttore dell'istanza
     * @param Mixed $value Valore del nodo
     * @param Array $child Figli del nodo
     * @param Mixed $parent Padre del nodo
     * @param Array $properties Proprietà estese del nodo
     */
    public function __construct($value, $child = null, $parent = null, array $properties = []) {
        $this->setValue($value);
        $this->setParent($parent);
        $this->extended_properties = new ExtendedProperties($properties);
        if (is_array($child)) {
            foreach ($child as $node) {
                $this->addNode($node);
            }
        } else {
            // throw exception ?
        }
    }

    /**
     * Imposta il valore dell'istanza appartenente alla struttura
     * @param type $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * Restituisce il valore dell'istanza appartenente alla struttura
     * @return Mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Aggiunge un nodo alla struttura. Se l'istanza è già un Node non ne crea
     * una nuova
     * @param Mixed|Node $node
     * @return Node Restituisce l'istanza node aggiunta
     */
    public function addNode($node) {
        $newnode = $node;
        if (!$node instanceof Node) {
            $newnode = new Node($node);
        }
        $newnode->parent = $this;
        $this->child[] = $newnode;
        return $newnode;
    }

    /**
     * Configura il nodo padre. Restituisce il nodo padre appena creato/aggiunto
     * @param Node|Mixed $parent
     * @return Node
     */
    public function setParent($parent) {
        if ($parent === $this->parent) {
            return;
        }
        $newparent = $parent;
        if (!$parent instanceof Node) {
            $newparent = new Node($parent);
        }
        if ($this->parent !== null) {
            $this->parent->removeNode($this);
        }
        $this->parent = $newparent;
        $newparent->addNode($this);
        return $newparent;
    }

    /**
     * Restituisce il Node padre
     * @return Node
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * Restituisce il Node alla posizione $index
     * @param int $index
     * @return bool|Mixed
     */
    public function getNode(int $index) {
        if ($this->hasNodePosition($index)) {
            return $this->child[$index];
        }
        return false;
    }

    /**
     * Restituisce la posizione dell'istanza $node
     * @param Node $node
     * @return int|bool
     */
    public function getPositionByNode(Node $node) {
        foreach ($this->child as $index => $cNode) {
            if ($cNode === $node) {
                return $index;
            }
        }
        return false;
    }

    /**
     * Restituisce la posizione del nodo in base al suo valore $value
     * @param type $value
     * @return int|bool
     */
    public function getPositionByValue($value) {
        foreach ($this->child as $index => $node) {
            if ($value === $node->getValue()) {
                return $index;
            }
        }
        return false;
    }

    /**
     * 
     * @param Restituisce un'istanza che ha valore $value
     * @return Node
     */
    public function getNodeByValue($value) {
        if ($this->hasNodeByValue($value)) {
            $index = $this->getPositionByValue($value);
            return $this->child[$index];
        }
        return false;
    }

    /**
     * Rerstituisce true o false se l'istanza $node è presente tra i figli
     * @param Node $node
     * @return bool
     */
    public function hasNode(Node $node) {
        return (array_search($node, $this->child, true) === false) ? false : true;
    }

    /**
     * Restituisce true o false se esiste un nodo alla posizione $pos
     * @param int $pos
     * @return bool
     */
    public function hasNodePosition(int $pos) {
        return array_key_exists($pos, $this->child);
    }

    /**
     * Restituisce true o false se un nodo con questo valore esiste
     * @param Mixed $value
     * @return bool
     */
    public function hasNodeByValue($value) {
        foreach ($this->child as $node) {
            if ($node->getValue() === $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Rimuove un nodo in base alla posizione
     * @param int $pos
     */
    public function removeNodeByPosition(int $pos) {
        if (array_key_exists($pos, $this->child)) {
            unset($this->child[$pos]);
        }
        $this->child = array_values($this->child);
    }

    /**
     * Rimuove un nodo data l'istanza
     * @param Node $node
     */
    public function removeNode(Node $node) {
        foreach ($this->child as $index => $n) {
            if ($n === $node) {
                $this->removeNodeByPosition($index);
            }
        }
    }

    /**
     * Rimuove un nodo in base al valore
     * @param Mixed $value
     */
    public function removeNodeByValue($value) {
        foreach ($this->child as $index => $n) {
            if ($n->getValue() === $value) {
                $this->removeNodeByPosition($index);
            }
        }
    }

    /**
     * Restituisce la distanza del figlio dal nodo "root"
     * @return int
     */
    public function getLevel() {
        return ($this->parent === null) ? 0 : ($this->getParent()->getLevel() + 1);
    }

    /**
     * Restituisce tutte le istanze Node figlie
     * Se $instance è true restituisce le istanze wrappate e non i Node
     * Se $instance è true e $include_child è true, verrà creato un array 
     * multidimensionale dove ogni valore sarà un array con le chiavi "value" e "child"
     * che conterranno rispettivamente il valore e i figli di quel nodo.
     * @paramm bool $instance
     * @return Node[]
     */
    public function getChildren(bool $instance = false, bool $include_child = false) {
        if ($instance === false) {
            return $this->child;
        }
        $children = [];
        foreach ($this->child as $node) {
            $children[] = ($include_child === false) ? $node->getValue() : ['value' => $node->getValue(), 'child' => $node->getChildren(true, $include_child)];
        }
        return $children;
    }

    /**
     * Restituisce la posizione di questa istanza tra i suoi nodi di pari livello
     * @return bool|int
     */
    public function findMyPosition() {
        if ($this->parent === null) {
            return false;
        }
        return $this->parent->getPositionByNode($this);
    }

    /**
     * Restituisce l'istanza fratello alla posizione $distance
     * $distance può essere un numero intero positivo o negativo.
     * Se positivo, cercherà i fratelli a distanza successiva (o destra) la propria
     * Se negativo, cercherà i fratelli a distanza precedente (o sinistra) la propria
     * @param int $distance
     * @return Node
     */
    public function getSibling(int $distance = 1) {
        if ($distance == 0) {
            return $this;
        }
        $index = ($this->findMyPosition() + $distance);
        return $this->parent->getNode($index);
    }

    public function getProperty(string $name) {
        return $this->extended_properties->getProperty($name);
    }

    public function setProperty(string $name, $value) {
        $this->extended_properties->setProperty($name, $value);
    }

}
