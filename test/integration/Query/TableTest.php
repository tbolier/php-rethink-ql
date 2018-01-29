<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Rethink;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class TableTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        if (!\in_array('tabletest', $this->r()->db()->tableList()->run()->getData()[0], true)) {
            $this->r()->db()->tableCreate('tabletest')->run();
        }
    }

    public function tearDown()
    {
        if (\in_array('tabletest', $this->r()->db()->tableList()->run()->getData()[0], true)) {
            $this->r()->db()->tableDrop('tabletest')->run();
        }

        parent::tearDown();
    }

    /**
     * @throws \Exception
     */
    public function testEmptyTable()
    {
        $count = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->count()
            ->run();

        $this->assertInternalType('int', $count->getData()[0]);

        $res = $this->r()
            ->table('tabletest')
            ->delete()
            ->run();

        $this->assertObStatus(['deleted' => $count->getData()[0]], $res->getData()[0]);
    }

    /**
     * @throws \Exception
     */
    public function testInsert()
    {
        $res = $this->insertDocument(1);

        $this->assertObStatus(['inserted' => 1], $res->getData()[0]);
    }

    /**
     * @throws \Exception
     */
    public function testCount()
    {
        $this->insertDocument(1);

        $res = $this->r()
            ->table('tabletest')
            ->count()
            ->run();

        $this->assertInternalType('int', $res->getData()[0]);
    }

    /**
     * @throws \Exception
     */
    public function testFilter()
    {
        $this->insertDocument(1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Test document',
                ],
            ])
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertInternalType('array', $cursor->current());
    }

    /**
     * @throws \Exception
     */
    public function testUpdate()
    {
        $this->insertDocument(1);

        $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'title' => 'Update document',
                ],
            ])
            ->run();

        $count = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Update document',
                ],
            ])
            ->count()
            ->run();

        $res = $this->r()
            ->table('tabletest')
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

        $this->assertObStatus(['replaced' => $count->getData()[0]], $res->getData()[0]);
    }

    /**
     * @throws \Exception
     */
    public function testDeleteDocument()
    {
        $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->run();

        $count = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->count()
            ->run();

        $this->assertInternalType('int', $count->getData()[0]);

        $res = $this->r()
            ->table('tabletest')
            ->filter([
                [
                    'title' => 'Delete document',
                ],
            ])
            ->delete()
            ->run();

        $this->assertObStatus(['deleted' => $count->getData()[0]], $res->getData()[0]);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGet(): void
    {
        $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'id' => 'foo',
                ],
            ])
            ->run();

        $res = $this->r()
            ->table('tabletest')
            ->get('foo')
            ->run();

        $this->assertEquals([0 => ['id' => 'foo']], $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetNonExistingDocument(): void
    {
        $res = $this->r()
            ->table('tabletest')
            ->get('bar')
            ->run();

        $this->assertEquals([0 => null], $res->getData());
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

    /**
     * @param int $documentId
     * @return ResponseInterface
     */
    private function insertDocument(int $documentId): ResponseInterface
    {
        $res = $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'documentId' => $documentId,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ],
            ])
            ->run();

        return $res;
    }
}
