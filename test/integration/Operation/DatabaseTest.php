<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

class DatabaseTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testTableList()
    {
        $res = $this->r()
                    ->db()
                    ->tableList()
                    ->run();

        $this->assertInternalType('array', $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testTableCreate()
    {
        $res = $this->r()
                    ->db()
                    ->tableCreate('createtable')
                    ->run();

        $this->assertObStatus(['tables_created' => 1], $res->getData());

        $this->r()
             ->db()
             ->tableDrop('createtable')
             ->run();
    }

    /**
     * @throws \Exception
     */
    public function testTableDrop()
    {
        $this->r()
             ->db()
             ->tableCreate('createtable')
             ->run();

        $res = $this->r()
                    ->db()
                    ->tableDrop('createtable')
                    ->run();

        $this->assertObStatus(['tables_dropped' => 1], $res->getData());
    }
}
