<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\Response\ResponseInterface;

class OrderByTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testLimit(): void
    {
        $this->insertDocument(5);
        $this->insertDocument(4);
        $this->insertDocument(3);
        $this->insertDocument(2);
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->orderBy('id')
            ->run();

        /** @var array $array */
        $array = $res->getData();

        $this->assertArraySubset(['id' => 1], $array[0]);
    }
}
