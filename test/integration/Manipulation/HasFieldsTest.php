<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Manipulation;

use TBolier\RethinkQL\IntegrationTest\Operation\AbstractTableTest;
use TBolier\RethinkQL\Response\Cursor;

class HasFieldsTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testTableHasField()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocumentWithNumber(3, 1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->hasFields('number')
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertEquals(1, $cursor->count());
    }

    /**
     * @throws \Exception
     */
    public function testHasField()
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

    /**
     * @throws \Exception
     */
    public function testHasFields()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocumentWithNumber(3, 1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($this->r()->row()->hasFields('id', 'number'))
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertEquals(1, $cursor->count());
    }

    /**
     * @throws \Exception
     */
    public function testHasFieldsNot()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocumentWithNumber(3, 1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($this->r()->row()->hasFields('id', 'number')->not())
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertEquals(2, $cursor->count());
    }
}
