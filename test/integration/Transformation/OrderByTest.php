<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Transformation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\ResponseInterface;

class OrderByTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testOrderByDesc(): void
    {
        $this->insertDocument(5);
        $this->insertDocument(4);
        $this->insertDocument(3);
        $this->insertDocument(2);
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->orderBy($this->r()->desc('id'))
            ->run();

        $this->assertArraySubset(['id' => 5], $res->getData()[0]);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testOrderByAsc(): void
    {
        $this->insertDocument(5);
        $this->insertDocument(4);
        $this->insertDocument(3);
        $this->insertDocument(2);
        $this->insertDocument(1);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->orderBy($this->r()->asc('id'))
            ->run();

        $this->assertArraySubset(['id' => 1], $res->getData()[0]);
    }
}
