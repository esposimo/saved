<?php


use PHPUnit\Framework\TestCase;
use smn\lazyc\dbc\Catalog\Table;
use smn\lazyc\dbc\Catalog\Schema;
use smn\lazyc\dbc\Catalog\SchemaInterface;
use smn\lazyc\dbc\Catalog\TableInterface;

class SchemaTest extends TestCase
{

    /**
     * @var \smn\lazyc\dbc\Catalog\Schema
     */
    protected $object;

    /**
     * @var string
     */
    protected $schemaName = 'schemaName';

    public function testGetAllTables()
    {
        $tables = [Table::createCatalogObjectInstance('table1'), Table::createCatalogObjectInstance('table2'), Table::createCatalogObjectInstance('table3')];
        foreach ($tables as $table) {
            $this->object->addTable($table);
        }

        $allTables = $this->object->getAllTables();
        $this->assertEquals(3, count($allTables));
        $table1 = $allTables[0];
        $table2 = $allTables[1];
        $table3 = $allTables[2];
        $this->assertInstanceOf(TableInterface::class, $table1, sprintf('I figli restituiti da getAllTables() devono essere di tipo %s', TableInterface::class));
        $this->assertInstanceOf(TableInterface::class, $table2, sprintf('I figli restituiti da getAllTables() devono essere di tipo %s', TableInterface::class));
        $this->assertInstanceOf(TableInterface::class, $table3, sprintf('I figli restituiti da getAllTables() devono essere di tipo %s', TableInterface::class));
    }

    public function test__construct()
    {
        $this->setUp();
        $this->assertEquals(0, count($this->object->getAllTables()), sprintf('In fase di inizializzazione della classe non devono esserci figli'));
        $this->assertEquals(0, count($this->object->getChildren()), sprintf('In fase di inizializzazione della classe non devono esserci figli'));
        $this->assertEquals($this->schemaName, $this->object->getName(), sprintf('Il nome dovrebbe essere %s e non %s', $this->schemaName, $this->object->getName()));
        $this->assertEquals(Schema::TYPENAME, $this->object->getType(), sprintf('Il tipo dovrebbe essere %s e non %s', Schema::TYPENAME, $this->object->getType()));
    }

    protected function setUp(): void
    {
        $this->object = new Schema($this->schemaName);
    }

    public function testRemoveTable()
    {
        $this->setUp();
        $tables = [Table::createCatalogObjectInstance('table1'), Table::createCatalogObjectInstance('table2'), Table::createCatalogObjectInstance('table3')];
        foreach ($tables as $table) {
            $this->object->addTable($table);
        }

        $this->object->removeTable('table2');
        $this->assertFalse($this->object->hasTable('table2'), sprintf('La tabella non dovrebbe più esistere'));
        $this->assertEquals(2, count($this->object->getAllTables()), sprintf('Le tabelle dovrebbero essere 2 ma risultano %s', count($this->object->getChildren(Table::TYPENAME))));
        $this->assertEquals(2, count($this->object->getChildren(Table::TYPENAME)), sprintf('Le tabelle restituite con il metodo ereditato getChildren() dovrebbero essere 2 ma sono %s', count($this->object->getChildren(Table::TYPENAME))));
        $table2 = $tables[1];
        $this->assertNull($table2->getParent(), sprintf('La tabella rimossa non deve avere più il padre'));
    }

    public function testAddTable()
    {
        $this->setUp();
        $table = Table::createCatalogObjectInstance('table');
        $this->object->addTable($table);
        $this->assertEquals(1, count($this->object->getAllTables()), sprintf('Dovrebbe essere solo 1 tabella, ne risultano %s', count($this->object->getAllTables())));
        $tableGet = $this->object->getTable('table');
        $this->assertSame($table, $tableGet, sprintf('La tabella aggiunta non risulta essere la stessa'));


    }

    public function testToString()
    {
        $this->setUp();
        $this->assertSame($this->schemaName, $this->object->toString());
    }

    public function testHasTable()
    {
        $this->setUp();
        $table = Table::createCatalogObjectInstance('table');
        $this->object->addTable($table);
        $this->assertTrue($this->object->hasTable('table'), sprintf('La tabella %s dovrebbe esistere', 'table'));
        $this->assertFalse($this->object->hasTable('table1'), sprintf('La tabella %s non dovrebbe esistere', 'table1'));
    }

    public function testGetTable()
    {
        $this->setUp();
        $table = Table::createCatalogObjectInstance('table');
        $this->object->addTable($table);
        $get = $this->object->getTable('table');
        $this->assertSame($table, $get, sprintf('La tabella aggiunta non risulta essere la stessa'));
    }

    public function testFactoryMethodTypeAsParameter()
    {
        $c = Schema::createCatalogObjectInstance('name', ['type' => 'sss']);
        $this->assertEquals(Schema::TYPENAME, $c->getType());
    }

}
