<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

class FilterTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testFilter()
    {
        $this->insertDocument(1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Test document 1'])
            ->run();

        /** @var array $array */
        $array = $cursor->current();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertArraySubset(['title' => 'Test document 1'], $array);
    }

    /**
     * @throws \Exception
     */
    public function testFilterOnMultipleDocuments()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Test document 1'])
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndIsEmpty(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['id' => 1])
            ->isEmpty()
            ->run();

        $this->assertFalse($res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndCount(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->count()
            ->run();

        $this->assertEquals(2, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndAvg(): void
    {
        $this->insertDocumentWithNumber(1, 50);
        $this->insertDocumentWithNumber(2, 100);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->avg('number')
            ->run();

        $this->assertEquals(75, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndSum(): void
    {
        $this->insertDocumentWithNumber(1, 50);
        $this->insertDocumentWithNumber(2, 100);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->sum('number')
            ->run();

        $this->assertEquals(150, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndLimit(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->limit(1)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndSkip(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->skip(1)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndOrderBy(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->orderBy($this->r()->desc('id'))
            ->run();

        $this->assertArraySubset(['id' => 'stringId'], $res->getData()[0]);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndMin(): void
    {
        $this->insertDocumentWithNumber(1, 77);
        $this->insertDocumentWithNumber(2, 99);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->min('number')
            ->run();

        $this->assertArraySubset(['number' => 77], $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndMax(): void
    {
        $this->insertDocumentWithNumber(1, 77);
        $this->insertDocumentWithNumber(2, 99);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->max('number')
            ->run();

        $this->assertArraySubset(['number' => 99], $res->getData());
    }
}
