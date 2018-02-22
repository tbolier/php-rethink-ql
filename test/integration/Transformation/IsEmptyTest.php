<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Transformation;

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

    /**
     * @throws \Exception
     */
    public function testFilterIsNotEmpty()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        $res = $this->r()
                    ->table('tabletest')
                    ->filter(['description' => 'A document description.'])
                    ->isEmpty()
                    ->run();

        $this->assertFalse($res->getData());
    }
}
