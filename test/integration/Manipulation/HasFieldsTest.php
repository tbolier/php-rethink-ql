<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Manipulation;

use TBolier\RethinkQL\IntegrationTest\Query\AbstractTableTest;
use TBolier\RethinkQL\Response\Cursor;

class HasFieldsTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testUniqueResult()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocumentWithNumber(3, 1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($this->r()->row()->hasFields('number'))
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertEquals(1, $cursor->count());
    }
}
