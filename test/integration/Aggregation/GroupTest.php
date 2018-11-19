<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Aggregation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class GroupTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testGroupByDescription(): void
    {
        $this->insertDocumentWithNumber(5, 10);
        $this->insertDocumentWithNumber(4, 10);
        $this->insertDocumentWithNumber(3, 20);
        $this->insertDocumentWithNumber(2, 20);
        $this->insertDocumentWithNumber(1, 30);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->group('description')
            ->run();

        $this->assertCount(1, $res->getData());

        $this->assertEquals('A document description.', $res->getData()[0]['group']);
        $this->assertCount(1, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGroupByNumber(): void
    {
        $this->insertDocumentWithNumber(5, 10);
        $this->insertDocumentWithNumber(4, 10);
        $this->insertDocumentWithNumber(3, 20);
        $this->insertDocumentWithNumber(2, 20);
        $this->insertDocumentWithNumber(1, 30);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->group('number')
            ->run();

        $this->assertCount(3, $res->getData());

        $this->assertEquals(10, $res->getData()[0]['group']);
        $this->assertCount(2, $res->getData()[0]['reduction']);

        $this->assertEquals(20, $res->getData()[1]['group']);
        $this->assertCount(2, $res->getData()[1]['reduction']);

        $this->assertEquals(30, $res->getData()[2]['group']);
        $this->assertCount(1, $res->getData()[2]['reduction']);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGroupAndMaxByNumberReductionByNumberAndUngroupOrderByNumber(): void
    {
        $this->insertDocumentWithNumber('Bob', 10);
        $this->insertDocumentWithNumber('Alice', 20);
        $this->insertDocumentWithNumber('Bob', 30);
        $this->insertDocumentWithNumber('Alice', 40);
        $this->insertDocumentWithNumber('Harry', 50);
        $this->insertDocumentWithNumber('Harry', 45);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->group('id')->max('number')->getField('number')
            ->ungroup()
            ->orderBy($this->r()->desc('number'))
            ->run();

        $this->assertCount(3, $res->getData());

        $this->assertEquals('Alice', $res->getData()[0]['group']);
        $this->assertEquals(20, $res->getData()[0]['reduction'][0]);

        $this->assertEquals('Bob', $res->getData()[1]['group']);
        $this->assertEquals(10, $res->getData()[1]['reduction'][0]);

        $this->assertEquals('Harry', $res->getData()[2]['group']);
        $this->assertEquals(50, $res->getData()[2]['reduction'][0]);
    }
}
