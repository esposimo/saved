<?php


use smn\lazyc\dbc\Catalog\Table;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{

    /**
     * @var Table
     */
    protected $object;

    /**
     * @var string
     */
    protected $tableName = 'table';

    public function testHasColumn()
    {
        $this->setUp();
        $column = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column');
        $this->object->addColumn($column);
        $this->assertTrue($this->object->hasColumn('column'), sprintf('La colonna %s dovrebbe essere presente', 'column'));
        $this->assertFalse($this->object->hasColumn('col'), sprintf('La colonna %s non dovrebbe essere presente', 'col'));
    }

    protected function setUp(): void
    {
        $this->object = Table::createCatalogObjectInstance($this->tableName);
    }

    public function test__construct()
    {
        $this->setUp();
        $this->assertEquals(0, count($this->object->getAllColumns()), sprintf('In fase di inizializzazione della classe non devono esserci figli'));
        $this->assertEquals(0, count($this->object->getChildren()), sprintf('In fase di inizializzazione della classe non devono esserci figli'));
        $this->assertEquals($this->tableName, $this->object->getName(), sprintf('Il nome dovrebbe essere %s e non %s', $this->tableName, $this->object->getName()));
        $this->assertEquals(Table::TYPENAME, $this->object->getType(), sprintf('Il tipo dovrebbe essere %s e non %s', Table::TYPENAME, $this->object->getType()));
    }

    public function testGetPrimaryKey()
    {
        $this->setUp();
        $this->assertFalse($this->object->getPrimaryKey(), sprintf('In assenza di primary key deve restituire false'));
        $column = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column');
        $pk = \smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraint::createConstraintInstance([$column]);
        $column->addConstraint($pk);
        $this->object->addColumn($column);
        $this->assertSame($this->object->getPrimaryKey(), $pk, sprintf('La primary key associata alla tabella non è quella aggiunta'));
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraintInterface::class, $this->object->getPrimaryKey(), sprintf('Dovrebbe esserci solo una primary key'));
    }

    public function testGetSchema()
    {
        $this->setUp();
        $schema = \smn\lazyc\dbc\Catalog\Schema::createCatalogObjectInstance('schema');
        $this->object->setSchema($schema);
        $this->assertSame($schema, $this->object->getSchema(), sprintf('Lo schema aggiunto non è lo stess'));
    }

    public function testGetColumn()
    {
        $this->setUp();
        $column = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column');
        $this->object->addColumn($column);
        $this->assertSame($column, $this->object->getColumn('column'), sprintf('La colonna aggiunta non è stata ritrovata'));
    }

    public function testRemoveColumn()
    {
        $this->setUp();
        $column = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column');
        $this->object->addColumn($column);
        $this->object->removeColumn('column');
        $this->assertNull($column->getTable(), sprintf('Dopo la rimozione la colonna non deve avere un padre'));
        $this->assertFalse($this->object->hasColumn('column'), sprintf('Doopo la rimozione la colonna è ancora presente'));
    }

    public function testSetSchema()
    {
        $this->setUp();
        $schema = new \smn\lazyc\dbc\Catalog\Schema('schema');
        $this->object->setSchema($schema);
        $this->assertSame($schema, $this->object->getSchema(), sprintf('Dopo aver assegnato lo schema alla tabella esso non è presente'));

    }

    public function testAddColumn()
    {
        $this->setUp();
        $column = new \smn\lazyc\dbc\Catalog\Column('column');
        $this->object->addColumn($column);
        $this->assertSame($column, $this->object->getColumn('column'), sprintf('Dopo aver aggiunto una colonna essa non risulta disponibile'));
        $this->assertTrue($this->object->hasColumn('column'),sprintf('Dopo aver aggiunto una colonna essa non risulta disponibile'));
    }

    public function testCreateCatalogObjectInstance()
    {
        $instance = Table::createCatalogObjectInstance('table');
        $this->assertEquals('table', $instance->getName(), sprintf('Il nome non corrisponde con quanto indicato'));
        $this->assertEquals(Table::TYPENAME, $instance->getType(), sprintf('Il tipo non risulta essere una tabella'));
        $this->assertEquals(0, count($instance->getAllColumns()), sprintf('Appena creata la classe non dovrebbe avere colonne'));
    }

    public function testGetAllColumns()
    {
        $this->setUp();
        $columns = [\smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column1'), \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column2'), \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column3'),];
        foreach ($columns as $column) {
            $this->object->addColumn($column);
        }
        $this->assertCount(3, $this->object->getAllColumns(), sprintf('Le colonne dovrebbero essere %s invece sono %s', 3, count($this->object->getAllColumns())));
    }

    public function testToString()
    {
        $this->setUp();
        $schema = \smn\lazyc\dbc\Catalog\Schema::createCatalogObjectInstance('schema');
        $this->assertEquals($this->tableName, $this->object->toString(), sprintf('Il metodo toString dovrebbe restituire solo il nome della tabella in mancanza di '));
        $this->object->setSchema($schema);
        $this->assertEquals(sprintf('%s.%s', $this->object->getSchema()->getName(), $this->object->getName()), $this->object->toString());
    }
}
