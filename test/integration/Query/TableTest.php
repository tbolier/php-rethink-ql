<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Rethink;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

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
        $this->r->db()->tableCreate('tablename');
    }

    /**
     * @throws \Exception
     */
    public function testEmptyTable()
    {
        $count = $this->r
            ->table('tablename')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->count()
            ->run();

        $this->assertInternalType('int', $count[0]);

        $res = $this->r
            ->table('tablename')
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
            ->table('tablename')
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
            ->table('tablename')
            ->count()
            ->run();

        $this->assertInternalType('int', $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testFilter()
    {
        $res = $this->r
            ->table('tablename')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->run();

        $this->assertInternalType('array', $res[0]);
    }

    /**
     * @throws \Exception
     */
    public function testUpdate()
    {
        $this->r
            ->table('tablename')
            ->insert([
                [
                    'title' => 'Update document',
                ],
            ])
            ->run();

        $count = $this->r
            ->table('tablename')
            ->filter([
                [
                    'title' => 'Update document',
                ],
            ])
            ->count()
            ->run();

        $res = $this->r
            ->table('tablename')
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
            ->table('tablename')
            ->insert([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->run();

        $count = $this->r
            ->table('tablename')
            ->filter([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->count()
            ->run();

        $this->assertInternalType('int', $count[0]);

        $res = $this->r
            ->table('tablename')
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
            ->table('tablename')
            ->insert([
                [
                    'id' => 'foo',
                ],
            ])
            ->run();

        $res = $this->r
            ->table('tablename')
            ->get('foo')
            ->run();

        $this->assertEquals([0 => ['id' => 'foo']], $res);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetNonExistingDocument(): void
    {
        $res = $this->r
            ->table('tablename')
            ->get('bar')
            ->run();

        $this->assertEquals([0 => null], $res);
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

        $this->assertEquals($status, $res);
    }
}
