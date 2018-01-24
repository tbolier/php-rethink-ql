<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Rethink;
use TBolier\RethinkQL\RethinkInterface;
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

        $this->assertInternalType('array', $res[0]);
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

        $this->assertObStatus(['tables_created' => 1], $res[0]);
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

        $this->assertObStatus(['tables_dropped' => 1], $res[0]);
    }

    /**
     * @param $status
     * @param $data
     * @throws \Exception
     */
    protected function assertObStatus($status, $data)
    {
        $res = [];
        $statuses = [
            'tables_created',
            'tables_dropped',
            'errors',
        ];
        $data = new ArrayObject($data);

        foreach ($statuses as $s) {
            $status[$s] = $status[$s] ?? 0;
        }

        $data->setFlags($data::ARRAY_AS_PROPS);

        foreach ($statuses as $s) {
            $res[$s] = $data[$s] ?? 0;
        }

        $this->assertEquals($status, $res);
    }
}
