<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Aggregation;

use TBolier\RethinkQL\IntegrationTest\Query\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class CountTest extends AbstractTableTest
{
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

        $this->assertInternalType('int', $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testGetAllAndCount()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $res */
        $res = $this->r()
                    ->table('tabletest')
                    ->getAll(1, 2, 3, 4, 5)
                    ->count()
                    ->run();

        $this->assertEquals(5, $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testFilterAndCount()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['id' => 1])
            ->count()
            ->run();

        $this->assertEquals(1, $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testFilterAndCountMultiple()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->count()
            ->run();

        $this->assertEquals(5, $res->getData());
    }
}
