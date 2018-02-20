<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Aggregation;

use TBolier\RethinkQL\IntegrationTest\Query\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class MaxTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testMax(): void
    {
        $this->insertDocument(5);
        $this->insertDocument(4);
        $this->insertDocument(3);
        $this->insertDocument(2);
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->max('number')
            ->run();

        /** @var array $array */
        $array = $res->getData();

        $this->assertArraySubset(['description' => 'A document description.'], $array);
    }
}
