<?php


use PHPUnit\Framework\TestCase;

class StructureFromCatalogTest extends TestCase
{


    protected $structure;

    protected function setUp(): void
    {

        $structure = [
            'name' => 'schema',
            'type' => \smn\lazyc\dbc\Catalog\Schema::class,
            'object_child' => [
                [
                    'name' => 'tabella1',
                    'type' => \smn\lazyc\dbc\Catalog\Table::class,
                    'object_child' => [
                        [
                            'name' => 'colonna1',
                            'type' => \smn\lazyc\dbc\Catalog\Column::class
                        ],
                        [
                            'name' => 'colonna2',
                            'type' => \smn\lazyc\dbc\Catalog\Column::class
                        ],
                        \smn\lazyc\dbc\Catalog\Column::createCatalogObjectInstance('colonna3')
                    ]
                ],
                [
                    'name' => 'tabella2',
                    'type' => 'table'
                ],
                \smn\lazyc\dbc\Catalog\Table::createCatalogObjectInstance('tabella3'),
                \smn\lazyc\dbc\Catalog\CatalogObject::createCatalogObjectInstance('tabella4', ['type' => \smn\lazyc\dbc\Catalog\Table::class])
            ]
        ];

        $this->structure = \smn\lazyc\dbc\Catalog\CatalogObject::createCatalogObjectInstance('schema', $structure);

    }


    public function testSchema()
    {
        $this->setUp();
        $this->assertEquals($this->structure->getName(), 'schema');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Schema::class, $this->structure);
    }

    public function testCheckTable()
    {
        $this->setUp();
        $this->assertCount(4, $this->structure->getAllTables());

        $table1 = $this->structure->getTable('tabella1');
        $table2 = $this->structure->getTable('tabella2');
        $table3 = $this->structure->getTable('tabella3');
        $table4 = $this->structure->getTable('tabella4');

        $this->assertEquals($table1->getName(), 'tabella1');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Table::class, $table1);

        $this->assertEquals($table2->getName(), 'tabella2');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\CatalogObject::class, $table2);
        $this->assertEquals('table', $table2->getType());

        $this->assertEquals($table3->getName(), 'tabella3');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Table::class, $table3);

        $this->assertEquals($table4->getName(), 'tabella4');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Table::class, $table4);

    }

    public function testColumnTable() {
        $this->setUp();
        $table = $this->structure->getTable('tabella1');

        $this->assertTrue($table->hasColumn('colonna1'));
        $this->assertTrue($table->hasColumn('colonna2'));
        $this->assertTrue($table->hasColumn('colonna3'));

        $colonna1 = $table->getColumn('colonna1');
        $colonna2 = $table->getColumn('colonna2');
        $colonna3 = $table->getColumn('colonna3');

        $this->assertEquals($colonna1->getName(), 'colonna1');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Column::class, $colonna1);

        $this->assertEquals($colonna2->getName(), 'colonna2');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Column::class, $colonna2);

        $this->assertEquals($colonna3->getName(), 'colonna3');
        $this->assertInstanceOf(\smn\lazyc\dbc\Catalog\Column::class, $colonna3);

    }

}


