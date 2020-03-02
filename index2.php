<?php

spl_autoload_register(function($class) {

    $replace = str_replace('\\', '/', $class);
    $explode = explode('/', $replace, 4);
    end($explode);
    $no_root = current($explode);

    $file = sprintf('%s/%s.php', __DIR__, $no_root);

    if (is_file($file)) {
        require_once $file;
    }
});

class ParentalInstance2 {

    /**
     *
     * @var ParentalInstance 
     */
    protected $parent = null;

    /**
     *
     * @var ParentalInstance[] 
     */
    protected $children = [];

    /**
     *
     * @var Mixed
     */
    protected $self = null;

    public function __construct($object) {
        $this->setInstance($object);
    }

    public function setInstance($object) {
        $this->self = $object;
    }

    public function getInstance() {
        return $this->self;
    }

    public function setParent($parent) {
        if ($parent instanceof ParentalInstance) {
            $this->parent = $parent;
        } else {
            $object = new self($parent);
            $this->parent = $object;
        }
    }

    public function getParent() {
        return $this->parent;
    }

    /**
     * 
     * @param type $object
     * @return \self
     */
    private function makeInstance($object) {
        if ($object instanceof ParentalInstance) {
            return $object;
        }
        return new self($object);
    }

    public function addChild(string $name, $object) {
        if (!$this->hasChild($name)) {
            $instance = $this->makeInstance($object);
            $this->children[$name] = $instance;
            $instance->setParent($this);
        }
    }

    public function hasChild(string $name) {
        return (array_key_exists($name, $this->children)) ? true : false;
    }

    public function getChild(string $name) {
        if ($this->hasChild($name)) {
            return $this->children[$name]->getInstance();
        }
        return null;
    }

    public function removeChild(string $name) {
        if ($this->hasChild($name)) {
            unset($this->children[$name]);
        }
    }

    public function getChildren() {
        $children = [];
        foreach ($this->children as $name => $object) {
            $children[$name] = $object->getInstance();
        }
        return $children;
    }

    public function getChildrenName() {
        return array_keys($this->children);
    }

    public function clearChildren() {
        $this->children = [];
    }

    public function removeParent() {
        $this->parent = null;
    }

}

interface ExtendedPropertiesInterface {

    public function setProperty(string $name, $value);

    public function getProperty(string $name);
}

class ExtendedProperties implements ExtendedPropertiesInterface {

    /**
     *
     * @var Array 
     */
    protected $extended_properties = [];

    /**
     *
     * @var bool 
     */
    public $allowOverride = true;

    public function getProperty(string $name) {
        return ($this->hasProperty($name)) ? $this->extended_properties[$name] : null;
    }

    public function setProperty(string $name, $value) {
        if ((($this->hasProperty($name)) && ($this->allowOverride)) || (!$this->hasProperty($name))) {
            $this->extended_properties[$name] = $value;
        }
    }

    public function hasProperty(string $name) {
        return array_key_exists($name, $this->extended_properties);
    }
    
    public function getProperties() {
        return $this->extended_properties;
    }

}

interface CatalogObjectInterface {

    public function getName();

    public function getType();
}

interface PrintableInterface {

    public function toString();
}

class CatalogObject implements CatalogObjectInterface {

    /**
     *
     * @var String 
     */
    protected $name;

    /**
     *
     * @var String 
     */
    protected $type;

    public function __construct(string $name, string $type) {
        $this->setName($name);
        $this->setType($type);
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function setName(string $name) {
        $this->name = $name;
    }

    public function setType(string $type) {
        $this->type = $type;
    }

}

class Node2 {

    /**
     *
     * @var Mixed 
     */
    protected $self;

    /**
     *
     * @var Node[] 
     */
    protected $list = [];

    /**
     *
     * @var Node 
     */
    protected $parent = null;

    /**
     *
     * @var String 
     */
    protected $name;

    /**
     *
     * @var ExtendedProperties 
     */
    protected $properties;

    public function __construct(string $name, $value) {
        $this->self = $value;
        $this->name = $name;
        $this->properties = new ExtendedProperties();
    }

    /**
     * 
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * 
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @param Mixed $value
     */
    public function setValue($value) {
        $this->self = $value;
    }

    /**
     * 
     * @return Mixed
     */
    public function getValue() {
        return $this->self;
    }

    /**
     * 
     * @param string $name
     * @return bool
     */
    public function hasObject(string $name) {
        return array_key_exists($name, $this->list);
    }

    /**
     * Aggiunge un figlio. Al figlio viene assegnato
     * come parent l'istanza attuale
     * @param string $name
     * @param Node $child
     * @return Node
     */
    public function addChild(string $name, $child) {
        if (!$this->hasObject($name)) {
            if ($child instanceof Node) {
                $this->list[$name] = $child;
                $child->setParent($this);
                return $child;
            } else {
                $instance = new Node($name, $child);
                $this->list[$name] = $instance;
                $instance->setParent($this);
                return $instance;
            }
        }
    }

    /**
     * 
     * @param string $name
     */
    public function removeObject(string $name) {
        if ($this->hasObject($name)) {
            unset($this->list[$name]);
        }
    }

    /**
     * Assegna un padre con nome parent
     * Al padre assegnato viene come figlio l'istanza 
     * @param Node $parent
     * @return type
     */
    public function setParent($parent) {
        if ($this->parent != null) {
            return;
        }
        if ($parent instanceof Node) {
            $parent->addChild($this->getName(), $this);
            $this->parent = $parent;
        } else {
            $instance = new Node('root', $parent);
            $instance->addChild($this->getName(), $this);
            $this->parent = $instance;
        }
    }

    /**
     * 
     * @return Node
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * 
     * @param string $name
     * @return Node
     */
    public function getChild(string $name) {
        if ($this->hasObject($name)) {
            return $this->list[$name];
        }
        return null;
    }

    /**
     * 
     * @return Node[]
     */
    public function getChildren() {
        return $this->list;
    }

    /**
     * 
     * @return Array
     */
    public function getChildrenValues() {
        $children = [];
        foreach ($this->list as $node) {
            $children[$node->getName()] = $node->getValue();
        }
        return $children;
    }

    /**
     * 
     * @return Array
     */
    public function getChildrenNames() {
        return array_keys($this->list);
    }

    /**
     * 
     * @return bool
     */
    public function hasParent() {
        return (is_null($this->parent)) ? true : false;
    }

    /**
     * 
     * @return void Description
     */
    public function clearChildren() {
        $this->list = [];
    }

    /**
     * 
     * @return void Description
     */
    public function removeParent() {
        $parent = $this->parent;
        $this->parent = null;
        $parent->clearChildren();
    }

    public function setProperty(string $name, $value) {
        $this->properties->setProperty($name, $value);
    }

    public function getProperty(string $name) {
        return $this->getProperty($name);
    }

}

class Node3 {

    /**
     *
     * @var Mixed 
     */
    protected $self;

    /**
     *
     * @var Node[] 
     */
    protected $list = [];

    /**
     *
     * @var Node 
     */
    protected $parent = null;

    /**
     *
     * @var ExtendedProperties 
     */
    protected $properties;

    public function __construct(string $name, $value) {
        $this->self = $value;
        $this->properties = new ExtendedProperties();
        $this->setName($name);
    }

    /**
     * 
     * @param string $name
     */
    public function setName(string $name) {
        $this->properties->setProperty('name', $name);
    }

    /**
     * 
     * @return String
     */
    public function getName() {
        return $this->properties->getProperty('name');
    }

    /**
     * 
     * @param Mixed $value
     */
    public function setValue($value) {
        $this->self = $value;
    }

    /**
     * 
     * @return Mixed
     */
    public function getValue() {
        return $this->self;
    }

    /**
     * Restituisce true o false se 
     * @param String $name
     * @return bool
     */
    public function hasObject(string $name) {
        $position = $this->getIndexPosition($name);
        return ($position === false) ? false : true;
    }

    public function getIndexPosition(string $name) {
        foreach ($this->list as $index => $node) {
            if ($node->getProperty('name') == $name) {
                return $index;
            }
        }
        return false;
    }

    /**
     * Aggiunge un figlio. Al figlio viene assegnato
     * come parent l'istanza attuale
     * Se $child è un node, $name viene sostituito dal nome del
     * $child
     * @param string $name
     * @param Node $child
     * @return Node
     */
    public function addChild(string $name, $child) {
        if (!$this->hasObject($name)) {
            if ($child instanceof Node) {
                $name = $child->getName();
//                $this->list[$name] = $child;
                $this->list[] = $child;
                $child->setParent($this);
                return $child;
            } else {
                $instance = new Node($name, $child);
//                $this->list[$name] = $instance;
                $this->list[] = $instance;
                $instance->setParent($this);
                return $instance;
            }
        }
    }

    /**
     * 
     * @param string $name
     */
    public function removeObject(string $name) {
        $index = $this->getIndexPosition($name);
        if ($index) {
            unset($this->list[$index]);
        }
    }

    /**
     * Assegna un padre con nome parent
     * Al padre assegnato viene come figlio l'istanza 
     * @param Node $parent
     * @return type
     */
    public function setParent($parent) {
        if ($this->parent != null) {
            return;
        }
        if ($parent instanceof Node) {
            $parent->addChild($this->getName(), $this);
            $this->parent = $parent;
        } else {
            $instance = new Node('root', $parent);
            $instance->addChild($this->getName(), $this);
            $this->parent = $instance;
        }
    }

    /**
     * 
     * @return Node
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * 
     * @param string $name
     * @return Node
     */
    public function getChild(string $name) {
        $index = $this->getIndexPosition($name);
        if ($index) {
            return $this->list[$name];
        }
        return null;
    }

    /**
     * 
     * @return Node[]
     */
    public function getChildren() {
        return $this->list;
    }

    /**
     * 
     * @return Array
     */
    public function getChildrenValues() {
        $children = [];
        foreach ($this->list as $node) {
            $children[$node->getName()] = $node->getValue();
        }
        return $children;
    }

    /**
     * 
     * @return Array
     */
    public function getChildrenNames() {
        return array_keys($this->list);
    }

    /**
     * 
     * @return bool
     */
    public function hasParent() {
        return (is_null($this->parent)) ? true : false;
    }

    /**
     * 
     * @return void Description
     */
    public function clearChildren() {
        $this->list = [];
    }

    /**
     * 
     * @return void Description
     */
    public function removeParent() {
        $parent = $this->parent;
        $this->parent = null;
        $parent->clearChildren();
    }

    public function setProperty(string $name, $value) {
        $this->properties->setProperty($name, $value);
    }

    public function getProperty(string $name) {
        return $this->properties->getProperty($name);
    }

}

class Node {

    /**
     *
     * @var Mixed 
     */
    protected $self;

    /**
     *
     * @var Node[] 
     */
    protected $list = [];

    /**
     *
     * @var Node 
     */
    protected $parent = null;

    /**
     *
     * @var ExtendedProperties 
     */
    protected $properties;

    public function __construct($value) {
        $this->self = $value;
        $this->properties = new ExtendedProperties();
    }

    /**
     * 
     * @param string $name
     */
    public function setProperty(string $name, $value) {
        $this->properties->setProperty($name, $value);
    }

    /**
     * 
     * @return String
     */
    public function getProperty(string $name) {
        return $this->properties->getProperty($name);
    }

    /**
     * 
     * @param Mixed $value
     */
    public function setValue($value) {
        $this->self = $value;
    }

    /**
     * 
     * @return Mixed
     */
    public function getValue() {
        return $this->self;
    }

    
    
    public function addNode($child) {
        foreach($this->list as $c) {
            if ($child === $c) {
                return null;
            }
        }
        if ($child instanceof Node) {
            $this->list[] = $child;
            $child->setParent($this);
            return $child;
        }
        else {
            $newnode = new Node($child);
            $this->list[] = $newnode;
            $newnode->setParent($this);
            return $newnode;
        }
    }
    
    
    public function removeNode(string $index) {
        if (array_key_exists($index, $this->list)) {
            unset($this->list[$index]);
        }
    }
    
    public function getNode(string $index) {
        if (array_key_exists($index, $this->list)) {
            return $this->list[$index];
        }
        return false;
    }
    
    public function getValueOfNode(string $index) {
        if ($this->getNode($index)) {
            return $this->getNode($index)->getValue();
        }
        return false;
    }

    
    public function setParent($parent) {
        if ($this->parent == null) {
            if ($parent instanceof Node) {
                $parent->addNode($this);
                $this->parent = $parent;
            }
            else {
                $instance = new Node($parent);
                $instance->addNode($this);
                $this->parent = $instance;
            }
        }
    }
    
    public function getProperties() {
        return $this->properties->getProperties();
    }
    
    public function getNodes() {
        return $this->list;
    }

}

class PlaceHolderSystem {

    protected $pattern;
    protected $placeholders = [];
    protected $params = [];

    public function setPattern($pattern) {
        $this->pattern = $pattern;
    }

    public function getPattern() {
        return $this->pattern;
    }

    public function setPlaceHolder($name, $value, $params = []) {
        $this->placeholders[$name] = $value;
        $this->params[$name] = $params;
    }

    public function getPlaceHolder($name) {
        return (array_key_exists($name, $this->placeholders)) ? $this->placeholders[$name] : null;
    }

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
                    $pms = (array_key_exists($matched, $this->params)) ? $this->params[$matched] : [];
                    return call_user_func_array($map[$matched], $pms);
                }
                return sprintf('%%%s$s', $key);
            }
// se non c'รจ un placeholder configurato lascia il placeholder
            return $p[0];
        }
                , $string);
        return vsprintf($return, $map);
    }

    public function render() {
        return $this->vksprintf();
    }

}

class ParentalCatalogObject implements CatalogObjectInterface {

    /**
     *
     * @var CatalogObject 
     */
    protected $catalogObject;

    /**
     *
     * @var Node
     */
    public $structure;

    /**
     *
     * @var PlaceHolderSystem 
     */
    protected $ph;

    public function __construct(string $name, string $type) {
        $this->catalogObject = new CatalogObject($name, $type);

        $this->structure = new Node($this);
        $this->structure->setProperty('name', $name);
        $this->structure->setProperty('type', $type);

        $this->ph = new PlaceHolderSystem();
        $this->ph->setPattern('{object_name}');
        $this->ph->setPlaceHolder('object_name', $name);
    }

    public function setName(string $name) {
        $this->catalogObject->setName($name);
        $this->structure->setProperty('name', $name);
    }

    public function setType(string $type) {
        $this->catalogObject->setType($type);
        $this->structure->setProperty('type', $type);
    }

   
    public function addObject(CatalogObjectInterface $object) {
        $name = $object->getName();
        $type = $object->getType();
        
        $newnode = $this->structure->addNode($object);
        $newnode->setProperty('name', $name);
        $newnode->setProperty('type', $type);
    }
    
    public function getObject(string $name, string $type) {
        $children = $this->structure->getNodes();
        foreach($children as $node) {
            if (($node->getProperty('name') == $name) && ($node->getProperty('type') == $type)) {
                return $node->getValue();
            }
        }
        return false;
    }

    public function getName() {
        return $this->catalogObject->getName();
    }

    public function getType() {
        return $this->catalogObject->getType();
    }

}

$schema = new ParentalCatalogObject('nomeschema', 'schema');
$table = new ParentalCatalogObject('nometabella', 'table');


$schema->addObject($table);
//$schema->removeObject('nometabella', 'table');

echo '<pre>';
print_r($schema->getObject('nometssabella', 'table'));
echo '</pre>';
