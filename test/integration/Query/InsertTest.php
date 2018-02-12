<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;

class InsertTest extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();

        if (!\in_array('tabletest', $this->r()->db()->tableList()->run()->getData(), true)) {
            $this->r()->db()->tableCreate('tabletest')->run();
        }
    }

    public function tearDown()
    {
        if (\in_array('tabletest', $this->r()->db()->tableList()->run()->getData(), true)) {
            $this->r()->db()->tableDrop('tabletest')->run();
        }

        parent::tearDown();
    }

    /**
     * @throws \Exception
     */
    public function testInsert()
    {
        $res = $this->insertDocument(1);

        $this->assertObStatus(['inserted' => 1], $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testMultipleInserts()
    {
        $res = $this->r()
            ->table('tabletest')
            ->insert([
                [
                    'documentId' => 1,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ],
                [
                    'documentId' => 2,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ],
                [
                    'documentId' => 3,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ]
            ])
            ->run();

        $this->assertObStatus(['inserted' => 3], $res->getData());
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
