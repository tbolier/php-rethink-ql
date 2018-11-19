<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

class GetAllTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAll(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var Cursor $cursor */
        $cursor = $this->r()
                       ->table('tabletest')
                       ->getAll(1, 'stringId')
                       ->run();

        /** @var array $array */
        $array = $cursor->current();

        $this->assertArraySubset(['description' => 'A document description.'], $array);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndIsEmpty(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->getAll(1, 'stringId')
            ->isEmpty()
            ->run();

        $this->assertFalse($res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndCount(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
                    ->table('tabletest')
                    ->getAll(1, 'stringId')
                    ->count()
                    ->run();

        $this->assertEquals(2, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndAvg(): void
    {
        $this->insertDocumentWithNumber(1, 50);
        $this->insertDocumentWithNumber(2, 100);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->getAll(1, 2)
            ->avg('number')
            ->run();

        $this->assertEquals(75, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndSum(): void
    {
        $this->insertDocumentWithNumber(1, 50);
        $this->insertDocumentWithNumber(2, 100);

        /** @var ResponseInterface $res */
        $res = $this->r()
                       ->table('tabletest')
                       ->getAll(1, 2)
                       ->sum('number')
                       ->run();

        $this->assertEquals(150, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndLimit(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $cursor = $this->r()
            ->table('tabletest')
            ->getAll(1, 'stringId')
            ->limit(1)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndSkip(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $cursor = $this->r()
            ->table('tabletest')
            ->getAll(1, 'stringId')
            ->skip(1)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndOrderBy(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->getAll(1, 'stringId')
            ->orderBy($this->r()->desc('id'))
            ->run();

        $this->assertArraySubset(['id' => 'stringId'], $res->getData()[0]);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndMin(): void
    {
        $this->insertDocumentWithNumber(1, 77);
        $this->insertDocumentWithNumber(2, 99);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->getAll(1, 2)
            ->min('number')
            ->run();

        $this->assertArraySubset(['number' => 77], $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndMax(): void
    {
        $this->insertDocumentWithNumber(1, 77);
        $this->insertDocumentWithNumber(2, 99);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->getAll(1, 2)
            ->max('number')
            ->run();

        $this->assertArraySubset(['number' => 99], $res->getData());
    }
}
