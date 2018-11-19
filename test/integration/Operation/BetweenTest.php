<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class BetweenTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testBetweenMin()
    {
        $this->insertDocument(1);
        $this->insertDocument(3);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->between(1, 2)
            ->run();

        /** @var array $array */
        $array = $cursor->current();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertEquals(1, $cursor->count());
        $this->assertArraySubset(['title' => 'Test document 1'], $array);
    }

    /**
     * @throws \Exception
     */
    public function testBetweenMax()
    {
        $this->insertDocument(1);
        $this->insertDocument(3);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->between(2, 3)
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertCount(0, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testBetweenMultiple()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->between(2, 4)
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertCount(2, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testBetweenNoResult(): void
    {
        $this->insertDocument('stringId');

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->between(2, 4)
            ->run();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertCount(0, $cursor);
    }
}
