<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

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
    public function testFilterCount()
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
    public function testFilterCountOnMultipleDocuments()
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
