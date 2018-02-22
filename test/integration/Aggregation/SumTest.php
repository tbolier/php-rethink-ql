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
        $this->insertDocumentWithNumber(5, 5);
        $this->insertDocumentWithNumber(4, 10);
        $this->insertDocumentWithNumber(3, 15);
        $this->insertDocumentWithNumber(2, 20);
        $this->insertDocumentWithNumber(1, 25);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->sum('number')
            ->run();

        $this->assertEquals(75, $res->getData());
    }
}
