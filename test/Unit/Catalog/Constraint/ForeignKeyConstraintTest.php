<?php


use smn\lazyc\dbc\Catalog\Constraint\ForeignKeyConstraint;
use PHPUnit\Framework\TestCase;

class ForeignKeyConstraintTest extends TestCase
{

    /**
     * @var Constraint
     */
    protected $object;

    /**
     * @var \smn\lazyc\dbc\Catalog\Column
     */
    protected $column;

    /**
     * @var string
     */
    protected $constraintName = 'cname';

    protected function setUp(): void
    {
        $this->column = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column');
        $table = \smn\lazyc\dbc\Catalog\Table::createCatalogObjectInstance('table');
        $table->addColumn($this->column);
        $this->object = ForeignKeyConstraint::createConstraintInstance([$this->column]);
        $this->object->setName($this->constraintName);
    }

    public function testReferencesTo()
    {
        $this->setUp();
        $this->object->referencesTo($this->column);
        $this->assertSame($this->column->getTable(), $this->object->getTableReference(),'La tabella della colonna alla quale fa riferimento la FK non è la stessa configurata');

    }

    public function testGetTableReference()
    {
        $this->setUp();
        $this->object->referencesTo($this->column);
        $this->assertSame($this->column->getTable(), $this->object->getTableReference(),'La tabella della colonna alla quale fa riferimento la FK non è la stessa configurata');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\TableInterface::class, $this->object->getTableReference());

    }

    public function testCreateConstraintInstance()
    {
        $column = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column');
        $fk = ForeignKeyConstraint::createConstraintInstance([$this->column],['name' => 'FK','reference' => $column]);
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Constraint\ForeignKeyConstraintInterface::class, $fk);
        $this->assertCount(1, $column->getForeignKey());
        $this->assertSame($fk, $column->getForeignKey()[0]);

    }
}
