<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Aggregation;

use TBolier\RethinkQL\IntegrationTest\Query\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class SumTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testSum(): void
    {
        $this->insertDocument(5);
        $this->insertDocument(4);
        $this->insertDocument(3);
        $this->insertDocument(2);
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->sum('number')
            ->run();

        $this->assertInternalType('int', $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndSum(): void
    {
        $this->insertDocument(5);
        $this->insertDocument(4);
        $this->insertDocument(3);
        $this->insertDocument(2);
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
                    ->table('tabletest')
                    ->filter(['description' => 'A document description.'])
                    ->sum('number')
                    ->run();

        $this->assertInternalType('int', $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndSum(): void
    {
        $this->insertDocument(5);
        $this->insertDocument(4);
        $this->insertDocument(3);
        $this->insertDocument(2);
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
                    ->table('tabletest')
                    ->getAll(1, 2, 3, 4, 5)
                    ->sum('number')
                    ->run();

        $this->assertInternalType('int', $res->getData());
    }
}
