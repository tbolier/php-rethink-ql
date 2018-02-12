<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class DatabaseTest extends BaseTestCase
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
    public function testCreateTable()
    {
        $res = $this->r()
            ->db()
            ->tableCreate('createtable')
            ->run();

        $this->assertObStatus(['tables_created' => 1], $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testDropTable()
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
