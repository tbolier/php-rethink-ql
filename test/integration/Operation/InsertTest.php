<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use ArrayObject;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\IntegrationTest\AbstractTestCase;

class InsertTest extends AbstractTableTest
{
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
                    'id' => 1,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ],
                [
                    'id' => 2,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ],
                [
                    'id' => 3,
                    'title' => 'Test document',
                    'description' => 'My first document.',
                ]
            ])
            ->run();

        $this->assertObStatus(['inserted' => 3], $res->getData());
    }
}
