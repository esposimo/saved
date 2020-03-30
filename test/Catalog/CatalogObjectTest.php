<?php

namespace smn\lazyc\dbc\Catalog;

use PHPUnit\Framework\TestCase;

class CatalogObjectTest extends TestCase
{

    /**
     * @var CatalogObject
     */
    protected $objectParent;

    /**
     * @var CatalogObject
    */
    protected $objectChild;

    /**
     * @var string
     */
    protected $parentName = 'padre';

    /**
     * @var string
     */
    protected $parentType = 'tipoPadre';

    /**
     * @var string
     */
    protected $childName = 'figlio';

    /**
     * @var string
     */
    protected $childType = 'tipoFiglio';


    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->objectParent = new CatalogObject($this->parentName,  $this->parentType);
        $this->objectChild = new CatalogObject($this->childName, $this->childType);
    }


    /**
     * @throws \Exception
     */
    public function testHasChild()
    {
        $this->setUp();
        $this->objectParent->addChild($this->objectChild);
        $bool = $this->objectParent->hasChild($this->childName, $this->childType);
        $this->assertTrue($bool);

    }

    /**
     * Questo test serve a verificare se il metodo setType funziona. Il metodo setType deve garantire che il nome del tipo
     * di oggetto di catalogo cambi. Il nome del tipo deve essere in formato stringa
     *
     * @throws \Exception
     */
    public function testSetType()
    {
        $this->setUp();
        $newtype = 'newType';
        $this->objectParent->setType($newtype);
        $this->assertIsString($this->objectParent->getType(),'Il tipo restitito non è in formato stringa');
        $this->assertEquals($newtype,$this->objectParent->getType(),sprintf('Il tipo restituito dal figlio non è %s', $newtype));
    }

    /**
     * Il test su addChild() serve a verificare che un figlio venga aggiunto correttamente. Viene testato il numero di figli totali,
     * la tipologia di figlio precedentemente aggiunto
     *
     * @throws \Exception
     */
    public function testAddChild()
    {
        $this->setUp();
        $this->assertIsInt(count($this->objectParent->getChildren()));
        $this->assertEquals(0, count($this->objectParent->getChildren()),'A classe istanziata il numero di figli dovrebbe essere 0');
        $this->objectParent->addChild($this->objectChild);
        $child = $this->objectParent->getChild($this->childName, $this->childType);
        $count = count($this->objectParent->getChildren());
        $this->assertIsInt($count);
        $this->assertEquals(1, $count);

        $this->assertInstanceOf(CatalogObject::class, $child);
        $this->assertSame($child, $this->objectChild);

    }

    /**
    * Il metodo setParent configura un padre ad un figlio. Nel test viene verificato se questo avviene
    */
    public function testSetParent()
    {
        $this->setUp();
        $this->assertNull($this->objectChild->getParent(),'La classe figlio ha già un parent, non dovrebbe');

        $this->objectChild->setParent($this->objectParent);
        $this->assertSame($this->objectParent, $this->objectChild->getParent(),'La classe padre non è la stessa restituita da getParent()');
        $this->assertInstanceOf(CatalogObject::class, $this->objectChild->getParent(),sprintf('Il tipo di classe padre dovrebbe essere %s', CatalogObject::class));

    }

    /**
     * Il metodo getName deve restituire una stringa contenente il nome dell'oggetto di catalogo
     */
    public function testGetName()
    {
        $this->setUp();
        $this->assertEquals($this->objectParent->getName(), $this->parentName);
        $this->assertIsString($this->objectParent->getName());

    }

    /**
     * In questo test vengono aggiunti due figli di diverso tipo. Verrà testato il metodo getChildren() sia con filtro che senza
     */
    public function testGetChildren()
    {
        $this->setUp();
        $newName = 'otherChild';
        $newType = 'otherType';
        $newInstance = new CatalogObject($newName, $newType);
        $this->objectParent->addChild($this->objectChild);
        $this->objectParent->addChild($newInstance);

        $allChildren = $this->objectParent->getChildren();
        $filterChildren = $this->objectParent->getChildren($newType);
        $this->assertIsArray($allChildren,'I figli dovrebbero essere restituiti in formato array');
        $this->assertIsArray($filterChildren,'I figli dovrebbero essere restituiti in formato array');
        $this->assertEquals(2,count($allChildren),sprintf('Sono stati aggiunti due figli ma ne viene restituito solo %s', count($allChildren)));
        $this->assertEquals(1, count($filterChildren),sprintf('Esiste solo un figlio di tipo %s ma vengono restituiti %s figli di tipo %s', $newType, count($filterChildren), $newType));
        $this->assertSame($newInstance, $filterChildren[0],'Il figlio ricavato con il filtro non corrisponde');

    }

    public function testRemoveChildByInstance()
    {
        $this->setUp();
        $this->assertEquals(0, count($this->objectParent->getChildren()),sprintf('I figli dovrebbero essere 0 invece sono %s', count($this->objectParent->getChildren())));
        $this->objectParent->addChild($this->objectChild);
        $this->assertEquals(1, count($this->objectParent->getChildren()),sprintf('Dovrebbe essere 1 figlio invece sono %s', count(($this->objectParent->getChildren()))));
        $this->objectParent->removeChildByInstance($this->objectChild);
        $this->assertEquals(0, count($this->objectParent->getChildren()),sprintf('I figli dovrebbero essere 0 invece sono %s', count($this->objectParent->getChildren())));
    }

    public function testSetName()
    {
        $this->setUp();
        $newName = 'newName';
        $this->objectParent->setName($newName);
        $this->assertEquals($newName, $this->objectParent->getName(),sprintf('Il nome dovrebbe essere %s ma è ancora %s', $newName, $this->objectParent->getName()));

    }

    public function testRemoveChild()
    {
        $this->setUp();
        $this->assertEquals(0, count($this->objectParent->getChildren()),sprintf('I figli dovrebbero essere 0 invece sono %s', count($this->objectParent->getChildren())));
        $this->objectParent->addChild($this->objectChild);
        $this->assertEquals(1, count($this->objectParent->getChildren()),sprintf('Dovrebbe essere 1 figlio invece sono %s', count(($this->objectParent->getChildren()))));
        $name = $this->objectChild->getName();
        $type = $this->objectChild->getType();
        $this->objectParent->removeChild($name, $type);
        $this->assertEquals(0, count($this->objectParent->getChildren()),sprintf('I figli dovrebbero essere 0 invece sono %s', count($this->objectParent->getChildren())));
    }

    public function test__construct()
    {
        $this->setUp();
        $this->assertEquals(0, count($this->objectParent->getChildren()),sprintf('I figli dovrebbero essere 0 invece sono %s', count($this->objectParent->getChildren())));
        $this->assertEquals($this->objectParent->getName(), $this->parentName,sprintf('Dopo aver istanziato la classe il nome non dovrebbe restare invariato invece risulta cambiato'));
        $this->assertEquals($this->objectParent->getType(), $this->parentType,sprintf('Dopo aver istanziato la classe il nome non dovrebbe restare invariato invece risulta cambiato'));
    }

    public function testSetEncoding()
    {
        $this->setUp();
        $encoding = new Encoding('utf8');
        $this->assertNull($this->objectParent->getEncoding(),'La classe appena istanziata non deve avere nessun Encoding');
        $this->objectParent->setEncoding($encoding);
        $this->assertSame($encoding, $this->objectParent->getEncoding(),'Non è stato restituita la stessa istanza di encoding');

    }

    public function testGetChild()
    {
        $this->setUp();
        $this->assertNull($this->objectParent->getChild($this->childName, $this->childType),sprintf('In fase di creazione della classe non dovrebbe esserci nessun figlio'));
        $this->objectParent->addChild($this->objectChild);
        $fakeChild = $this->objectParent->getChild($this->childName,'fakeType');
        $this->assertNull($fakeChild,sprintf('In mancanza di un figlio trovato dovrebbe essere restituito null'));
        $child = $this->objectParent->getChild($this->childName, $this->childType);
        $this->assertSame($child, $this->objectChild,sprintf('Il figlio non è stato restituito'));

    }

    public function testGetType()
    {
        $this->setUp();
        $this->assertEquals($this->parentType, $this->objectParent->getType(),sprintf('Il tipo restituito dovrebbe essere %s', $this->parentType));

    }

    public function testGetEncoding()
    {
        $this->setUp();
        $encoding = new Encoding('utf8');
        $this->objectParent->setEncoding($encoding);
        $this->assertSame($encoding, $this->objectParent->getEncoding(),'Encoding non corrispondente');

    }

    public function testCreateCatalogObjectInstance()
    {
        $name = 'factoryName';
        $this->assertFalse(CatalogObject::createCatalogObjectInstance($name));
    }

    public function testGetParent()
    {
        $this->setUp();
        $this->objectParent->addChild($this->objectChild);
        $this->assertSame($this->objectParent, $this->objectChild->getParent(),'Il padre non viene configurato correttamente');
        $this->setUp();
        $this->objectChild->setParent($this->objectParent);
        $this->assertSame($this->objectParent, $this->objectChild->getParent(),'Il padre non viene configurato correttamente');

    }

    public function testHasType()
    {
        $this->setUp();
        $this->assertFalse($this->objectParent->hasType($this->childType), sprintf('Appena creato un padre non può avere figli di nessun tipo'));
        $this->objectParent->addChild($this->objectChild);
        $this->assertFalse($this->objectParent->hasType('fakeType'), sprintf('Non è stato aggiunto nessun figlio di tipo fakeType'));
        $this->assertTrue($this->objectParent->hasType($this->childType), sprintf('Dovrebbe esserci almeno un figlio di tipo %s', $this->childType));

    }

    public function testHasChildByInstance()
    {
        $this->setUp();
        $this->assertFalse($this->objectParent->hasChildByInstance($this->objectChild),sprintf('Il padre non dovrebbe avere nessun figlio di nessun tipo'));
        $this->objectParent->addChild($this->objectChild);
        $this->assertTrue($this->objectParent->hasChildByInstance($this->objectChild), sprintf('Il padre dovrebbe avere almeno un figlio'));

    }

    public function testRemoveParent() {
        $this->setUp();
        $this->objectParent->addChild($this->objectChild);
        $this->objectChild->removeParent();
        $this->assertNull($this->objectChild->getParent(),sprintf('La classe figlio non dovrebbe più avere padri'));
        $this->assertEquals(0, count($this->objectParent->getChildren()),sprintf('La classe padre non dovrebbe più avere figli'));
    }
}
