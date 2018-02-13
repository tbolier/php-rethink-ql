<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\Response\ResponseInterface;

class OrderByTest extends AbstractTableTest
{
    /**
     * @var array
     */
    private $array;

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
            ->orderby('id')
            ->run();

        $this->array = $res->getData();

        $this->assertArraySubset(['id' => 1], $this->array[0]);
    }
}
