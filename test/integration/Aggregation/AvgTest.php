<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Aggregation;

use TBolier\RethinkQL\IntegrationTest\Query\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class AvgTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testAvg(): void
    {
        $this->insertDocument(5);
        $this->insertDocument(4);
        $this->insertDocument(3);
        $this->insertDocument(2);
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->avg('number')
            ->run();

        $this->assertTrue(is_float($res->getData()) || is_int($res->getData()));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndAvg(): void
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
                    ->avg('number')
                    ->run();

        $this->assertTrue(is_float($res->getData()) || is_int($res->getData()));
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetAllAndAvg(): void
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
                    ->avg('number')
                    ->run();

        $this->assertTrue(is_float($res->getData()) || is_int($res->getData()));
    }
}
