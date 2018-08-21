<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Manipulation;

use TBolier\RethinkQL\IntegrationTest\Query\AbstractTableTest;

class ValuesTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testValuesResult()
    {
        $this->insertDocumentWithNumber(1, 1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->get(1)
            ->values()
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertEquals(1, $cursor->count());
    }
}
