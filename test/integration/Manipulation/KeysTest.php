<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Manipulation;

use TBolier\RethinkQL\IntegrationTest\Query\AbstractTableTest;

class KeysTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testKeysResult()
    {
        $this->insertDocumentWithNumber(1, 1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->get(1)
            ->keys()
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertEquals(1, $cursor->count());
    }
}
