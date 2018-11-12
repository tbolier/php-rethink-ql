<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Aggregation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class CountTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testCount()
    {
        $this->insertDocumentWithNumber(5, 5);
        $this->insertDocumentWithNumber(4, 10);
        $this->insertDocumentWithNumber(3, 15);
        $this->insertDocumentWithNumber(2, 20);
        $this->insertDocumentWithNumber(1, 25);

        $res = $this->r()
            ->table('tabletest')
            ->count()
            ->run();

        $this->assertEquals(5, $res->getData());
    }
}
