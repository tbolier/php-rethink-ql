<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Rethink;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Test\BaseTestCase;

class TableTest extends BaseTestCase
{
    /**
     * @var RethinkInterface
     */
    private $r;

    public function setUp()
    {
        parent::setUp();

        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();
        $connection->connect()->use('test');

        $this->r = new Rethink($connection);
    }


    /**
     * @throws \Exception
     */
    public function testEmptyTable()
    {
        $count = $this->r
            ->table('nl')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->count()
            ->run();

        static::assertInternalType('int', $count[0]);

        $res = $this->r
            ->table('nl')
            ->delete()
            ->run();

        $this->assertObStatus(['deleted' => $count[0]], $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testInsert()
    {
        $res = $this->r
            ->table('nl')
            ->insert([
                [
                    'documentId' => 1,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ],
            ])
            ->run();

        $this->assertObStatus(['inserted' => 1], $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testCount()
    {
        $res = $this->r
            ->table('nl')
            ->count()
            ->run();

        static::assertInternalType('int', $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testFilter()
    {
        $res = $this->r
            ->table('nl')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->run();

        static::assertInternalType('array', $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testUpdate()
    {
        $this->r
            ->table('nl')
            ->insert([
                [
                    'title' => 'Update document',
                ],
            ])
            ->run();

        $count = $this->r
            ->table('nl')
            ->filter([
                [
                    'title' => 'Update document',
                ],
            ])
            ->count()
            ->run();

        $res = $this->r
            ->table('nl')
            ->filter([
                [
                    'title' => 'Update document',
                ],
            ])
            ->update([
                [
                    'title' => 'Updated document',
                ],
            ])
            ->run();

        $this->assertObStatus(['replaced' => $count[0]], $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testDeleteDocument()
    {
        $this->r
            ->table('nl')
            ->insert([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->run();

        $count = $this->r
            ->table('nl')
            ->filter([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->count()
            ->run();

        static::assertInternalType('int', $count[0]);

        $res = $this->r
            ->table('nl')
            ->filter([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->delete()
            ->run();

        $this->assertObStatus(['deleted' => $count[0]], $res[0]);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGet(): void
    {
        $this->r
            ->table('nl')
            ->insert([
                [
                    'id' => 'foo',
                ],
            ])
            ->run();

        $res = $this->r
            ->table('nl')
            ->get('foo')
            ->run();

        static::assertEquals([0 => ['id' => 'foo']], $res);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetNonExistingDocument(): void
    {
        $res = $this->r
            ->table('nl')
            ->get('bar')
            ->run();

        static::assertEquals([0 => null], $res);
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
            'unchanged',
            'skipped',
            'replaced',
            'inserted',
            'errors',
            'deleted',
        ];
        $data = new ArrayObject($data);

        foreach ($statuses as $s) {
            $status[$s] = $status[$s] ?? 0;
        }

        $data->setFlags($data::ARRAY_AS_PROPS);

        foreach ($statuses as $s) {
            $res[$s] = $data[$s] ?? 0;
        }

        static::assertEquals($status, $res);
    }
}
