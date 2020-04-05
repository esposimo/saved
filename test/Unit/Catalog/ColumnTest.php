<?php


use PHPUnit\Framework\TestCase;

use smn\lazyc\dbc\Catalog\Column;


class ColumnTest extends TestCase
{

    /**
     * @var Column
     */
    protected $object;

    /**
     * @var string
     */
    protected $columnName = 'column';

    public function testSetTable()
    {
        $this->setUp();
        $table = \smn\lazyc\dbc\Catalog\Table::createCatalogObjectInstance('table');
        $this->assertNull($this->object->getTable(), sprintf('Se non configurata la colonna non deve avere una tabella padre'));
        $this->object->setTable($table);
        $this->assertSame($table, $this->object->getTable(), sprintf('La tabella padre non risulta essere corretta'));
    }

    protected function setUp(): void
    {
        $this->object = Column::createCatalogObjectInstance($this->columnName);
    }

    public function testIsPrimaryKey()
    {
        $this->setUp();
        $this->assertFalse($this->object->isPrimaryKey(), sprintf('A colonna creata non devono risultare primary key'));
        \smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraint::createConstraintInstance([$this->object]);
        $this->assertTrue($this->object->isPrimaryKey(), sprintf('La primary key associata non risulta essere presente'));

    }

    public function testGetConstraints()
    {
        $this->setUp();
        $this->assertCount(0, $this->object->getConstraints(), sprintf('Appena creata la colonna non deve avere constraint di nessun tipo'));
        \smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraint::createConstraintInstance([$this->object]);
        $this->assertCount(1, $this->object->getConstraints(), sprintf('La primarykey aggiunta non risulta essere presente'));
        \smn\lazyc\dbc\Catalog\Constraint\NotNullConstraint::createConstraintInstance([$this->object]);
        $this->assertCount(2, $this->object->getConstraints(), sprintf('Dovrebbero essere presenti 2 constraint ne risulta %s', count($this->object->getConstraints())));
        \smn\lazyc\dbc\Catalog\Constraint\PrimaryKeyConstraint::createConstraintInstance([$this->object]);
        $this->assertCount(3, $this->object->getConstraints(), sprintf('La nuova primary key non dovrebbe essere aggiunta'));
    }

    public function testToString()
    {
        $schema = \smn\lazyc\dbc\Catalog\Schema::createCatalogObjectInstance('schema');
        $table = \smn\lazyc\dbc\Catalog\Table::createCatalogObjectInstance('table');
        $schema->addTable($table);
        $this->setUp();
        $table->addColumn($this->object);
        $name = sprintf('%s.%s.%s', $schema->getName(), $table->getName(), $this->object->getName());
        $this->assertEquals($name, $this->object->toString(), sprintf('Il FQDN dovrebbe essere %s e non %s', $name, $this->object->toString()));

    }

    public function testIsNotNull()
    {
        $this->setUp();
        $this->assertFalse($this->object->isNotNull(), sprintf('Appena creata la colonna non dovrebbe avere constraint'));
        \smn\lazyc\dbc\Catalog\Constraint\NotNullConstraint::createConstraintInstance([$this->object]);
        $this->assertTrue($this->object->isNotNull(), sprintf('La colonna dovrebbe avere una %s associata', \smn\lazyc\dbc\Catalog\Constraint\NotNullConstraint::class));
    }

    public function testGetIndex()
    {
        $this->setUp();
        $this->assertFalse($this->object->hasIndex(), sprintf('Appena creata la colonna non dovrebbe avere indici'));
        $index = \smn\lazyc\dbc\Catalog\Constraint\IndexConstraint::createConstraintInstance([$this->object]);
        $this->assertTrue($this->object->hasIndex(), sprintf('La colonna non ha l\'index appena aggiunto'));
        $this->assertCount(1, $this->object->getIndex(), sprintf('La colonna dovrebbe avere 1 indice ma se ne ritrova %s', count($this->object->getIndex())));
        $idx = $this->object->getIndex()[0];
        $this->assertSame($index, $idx, sprintf('L\'index aggiunto non è lo stesso'));
    }

    public function testHasDefault()
    {
        $this->setUp();
        $this->assertFalse($this->object->hasDefault(), sprintf('Appena creata la colonna non può avere constraint default'));
        \smn\lazyc\dbc\Catalog\Constraint\DefaultConstraint::createConstraintInstance([$this->object]);
        $this->assertTrue($this->object->hasDefault(), sprintf('La DefaultConstraint aggiunta non risulta esserci'));
    }

    public function testGetTable()
    {
        $this->setUp();
        $table = \smn\lazyc\dbc\Catalog\Table::createCatalogObjectInstance('table');
        $this->assertNull($this->object->getTable(), sprintf('Appena creata la colonna non deve avere una tabella padre'));
        $this->object->setTable($table);
        $this->assertSame($table, $this->object->getTable(), sprintf('La tabella padre della colonna non risutla essere la stessa aggiunta'));
    }

    public function testAddConstraint()
    {
        $this->setUp();
        $this->assertCount(0, $this->object->getConstraints(), sprintf('Appena creata la colonna non dovrebbe avere constraint'));
        $c = \smn\lazyc\dbc\Catalog\Constraint\IndexConstraint::createConstraintInstance([$this->object]);
        $this->assertCount(1, $this->object->getConstraints(), sprintf('La constraint aggiunta non risulta essere presente'));
        $cc = $this->object->getConstraints()[0];
        $this->assertSame($c, $cc, sprintf('La constraint aggiunta non risulta essere la stessa'));
    }

    public function testHasIndex()
    {
        $this->setUp();
        $this->assertFalse($this->object->hasIndex(), sprintf('Appena creata la colonna non dovrebbe avere nessuna constraint'));
        \smn\lazyc\dbc\Catalog\Constraint\IndexConstraint::createConstraintInstance([$this->object]);
        $this->assertTrue($this->object->hasIndex(), sprintf('L\'indice aggiunto non risulta essere preso in considerazione'));
    }

    public function testGetDefault()
    {
        $this->setUp();
        $this->assertFalse($this->object->hasDefault(), sprintf('Appena creata la colonna non dovrebbe avere nessuna constraint'));
        $def = \smn\lazyc\dbc\Catalog\Constraint\DefaultConstraint::createConstraintInstance([$this->object]);
        $this->assertCount(1, $this->object->getDefault(), sprintf('Dovrebbe esserci almeno una DefaultConstraint'));
        $defc = $this->object->getDefault()[0];
        $this->assertSame($defc, $def, sprintf('La DefaultConstraint associata non risulta essere la stessa'));
    }

    public function testGetCheck()
    {
        $this->setUp();
        $this->assertFalse($this->object->hasCheck(), sprintf('Appena creata la colonna non dovrebbe avere nessuna constraint'));
        $check = \smn\lazyc\dbc\Catalog\Constraint\CheckConstraint::createConstraintInstance([$this->object]);
        $this->assertcount(1, $this->object->getCheck(), sprintf('Dovrebbe essere almeno una CheckConstraint'));
        $checkc = $this->object->getCheck()[0];
        $this->assertSame($check, $checkc, sprintf('La CheckConstraint associata non risulta essere la stessa'));

    }

    public function test__construct()
    {
        $this->setUp();
        $this->assertEquals(0, count($this->object->getConstraints()), sprintf('In fase di inizializzazione della colonna non devono esserci constraint'));
        $this->assertEquals($this->columnName, $this->object->getName(), sprintf('Il nome dovrebbe essere %s e non %s', $this->columnName, $this->object->getName()));
        $this->assertEquals(Column::TYPENAME, $this->object->getType(), sprintf('Il tipo dovrebbe essere %s e non %s', Column::TYPENAME, $this->object->getType()));
        $this->assertNull($this->object->getTable(), sprintf('Appena creata la colonna non deve avere una tabella associata'));
    }

    public function testHasForeignKey()
    {

        $this->setUp();
        $this->assertFalse($this->object->hasForeignKey(), sprintf('Appena creata la colonna non deve avere ForeignKey'));
        \smn\lazyc\dbc\Catalog\Constraint\ForeignKeyConstraint::createConstraintInstance([$this->object]);
        $this->assertTrue($this->object->hasForeignKey(), sprintf('La foreign key aggiunta non risulta essere presente'));
    }

    public function testGetForeignKey()
    {
        $this->setUp();
        $this->assertFalse($this->object->hasForeignKey(), sprintf('Appena creata la colonna non deve avere ForeignKey'));
        $fk = \smn\lazyc\dbc\Catalog\Constraint\ForeignKeyConstraint::createConstraintInstance([$this->object]);
        $this->assertTrue($this->object->hasForeignKey(), sprintf('La foreign key aggiunta non risulta essere presente'));
        $fkc = $this->object->getForeignKey()[0];
        $this->assertSame($fk, $fkc, sprintf('La foreign key aggiunta non risulta essere la stessa'));

    }

    public function testHasCheck()
    {
        $this->setUp();
        $this->assertFalse($this->object->hasCheck(), sprintf('Appena creata la colonna non dovrebbe avere nessuna constraint'));
        $check = \smn\lazyc\dbc\Catalog\Constraint\CheckConstraint::createConstraintInstance([$this->object]);
        $this->assertcount(1, $this->object->getCheck(), sprintf('Dovrebbe essere almeno una CheckConstraint'));

    }
}
