<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\Response\Cursor;

class LimitTest extends AbstractTableTest
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testLimit(): void
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->limit(2)
            ->run();

        $this->assertCount(2, $cursor);
    }
}
