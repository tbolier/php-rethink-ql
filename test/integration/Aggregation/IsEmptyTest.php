<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Aggregation;

use TBolier\RethinkQL\IntegrationTest\Query\AbstractTableTest;

class IsEmptyTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testIsEmpty()
    {
        $res = $this->r()
                    ->table('tabletest')
                    ->isEmpty()
                    ->run();

        $this->assertTrue($res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testIsNotEmpty()
    {
        $this->insertDocument(1);

        $res = $this->r()
                    ->table('tabletest')
                    ->isEmpty()
                    ->run();

        $this->assertFalse($res->getData());
    }
}
