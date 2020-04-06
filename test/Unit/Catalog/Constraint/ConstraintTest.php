<?php


use smn\lazyc\dbc\Catalog\Constraint\Constraint;
use PHPUnit\Framework\TestCase;

class ConstraintTest extends TestCase
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

    public function testGetTable()
    {

        $this->setUp();
        $table = \smn\lazyc\dbc\Catalog\Table::createCatalogObjectInstance('table');
        $this->column->setTable($table);
        $this->assertSame($table, $this->object->getTable(), sprintf('La tabella restituita non è corretta'));

    }

    protected function setUp(): void
    {
        $this->column = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column');
        $this->object = new smn\lazyc\dbc\Catalog\Constraint\Constraint([$this->column]);
        $this->object->setName($this->constraintName);
    }

    public function testGetName()
    {
        $this->setUp();
        $this->assertEquals($this->constraintName, $this->object->getName(), sprintf('Il nome dovrebbe essere %s ma risulta essere %s', $this->constraintName, $this->object->getName()));

    }

    public function testRemoveRelationTo()
    {
        $this->setUp();
        $this->assertCount(1, $this->object->getRelations(), sprintf('Attesa una sola colonna relaazionata'));
        $this->object->removeRelationTo($this->column->getName());
        $this->assertCount('0', $this->object->getRelations(), sprintf('Attese 0 relazioni'));

    }

    public function testSetType()
    {
        $this->setUp();
        $this->object->setType(Constraint::CONSTRAINT_CHECK);
        $this->assertEquals(Constraint::CONSTRAINT_CHECK, $this->object->getType(), sprintf('Il tipo dovrebbe essere %s ma risulta essere %s', Constraint::CONSTRAINT_CHECK, $this->object->getType()));

    }

    public function testRelationTo()
    {
        $this->setUp();
        $this->assertCount(1, $this->object->getRelations(), sprintf('Attesa una sola colonna relaazionata'));
        $c = $this->object->getRelations()[0];
        $this->assertSame($c, $this->column, sprintf('La relazione non è configurata correttamente'));

    }

    public function testGetType()
    {
        $this->setUp();
        $this->object->setType(Constraint::CONSTRAINT_DEFAULT);
        $this->assertEquals($this->object->getType(), Constraint::CONSTRAINT_DEFAULT, sprintf('Il tipo restituito non è corretto'));

    }

    public function test__construct()
    {
        $c = new Constraint();
        $this->assertCount(0, $c->getRelations(), sprintf('Se non viene indicato nel costruttore, la constraint non deve avere colonne referenziate'));
        $c = new Constraint([\smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column')]);
        $this->assertCount(1, $c->getRelations(), sprintf('La constraint è stata inizializzata con una colonna ma essa non risulta'));

    }

    public function testSetName()
    {
        $this->setUp();
        $newname = 'newname';
        $this->object->setName($newname);
        $this->assertEquals($newname, $this->object->getName(), sprintf('Il nome configurato non coincide con quello restituito'));
    }

    public function testGetRelations()
    {
        $this->setup();
        $this->assertCount(1, $this->object->getRelations(), sprintf('La constraint dovrebbe avere una colonna relazionata'));
    }

    public function testIsRelatedTo()
    {
        $this->setUp();
        $c = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('newcolumn');
        $this->assertTrue($this->object->isRelatedTo($this->column->getName()), sprintf('La constraint non risulta relazionata con la colonna'));
        $this->assertFalse($this->object->isRelatedTo('newcolumn'), sprintf('La constraint risulta relazionata ad una colonna errata'));
    }

    public function testCreateConstraintInstance() {

        $column = \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('column');
        $c = Constraint::createConstraintInstance([$column],['name' => 'cname']);
        $this->assertFalse($c); // La classe Constraint può istanziare una constraint generica?
        $c = \smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraint::createConstraintInstance([$column],['name' => 'cname']);
        $this->assertSame('cname', $c->getName());
        $this->assertCount(1, $c->getRelations());
        $relation = $c->getRelations();
        $this->assertSame($column, $relation[0]);
      }

}
