<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Query;

use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;

class FilterTest extends AbstractTableTest
{
    /**
     * @throws \Exception
     */
    public function testFilter()
    {
        $this->insertDocument(1);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Test document 1'])
            ->run();

        /** @var array $array */
        $array = $cursor->current();

        $this->assertInstanceOf(\Iterator::class, $cursor);
        $this->assertArraySubset(['title' => 'Test document 1'], $array);
    }

    /**
     * @throws \Exception
     */
    public function testFilterOnMultipleDocuments()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var Cursor $cursor */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter(['title' => 'Test document 1'])
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testFilterAndAvg()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $cursor */
        $res = $this->r()
                       ->table('tabletest')
                       ->filter(['title' => 'Test document 1'])
                       ->avg('number')
                       ->run();

        $this->assertTrue(is_float($res->getData()) || is_int($res->getData()));
    }

    /**
     * @throws \Exception
     */
    public function testFilterAndSum()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $cursor */
        $res = $this->r()
                    ->table('tabletest')
                    ->filter(['title' => 'Test document 1'])
                    ->sum('number')
                    ->run();

        $this->assertTrue(is_float($res->getData()) || is_int($res->getData()));
    }

    /**
     * @throws \Exception
     */
    public function testFilterAndMin()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $cursor */
        $res = $this->r()
                    ->table('tabletest')
                    ->filter(['title' => 'Test document 1'])
                    ->min('number')
                    ->run();

        /** @var array $array */
        $array = $res->getData();

        $this->assertArraySubset(['description' => 'A document description.'], $array);
    }

    /**
     * @throws \Exception
     */
    public function testFilterAndMax()
    {
        $this->insertDocument(1);
        $this->insertDocument(2);
        $this->insertDocument(3);
        $this->insertDocument(4);
        $this->insertDocument(5);

        /** @var ResponseInterface $cursor */
        $res = $this->r()
                    ->table('tabletest')
                    ->filter(['title' => 'Test document 1'])
                    ->max('number')
                    ->run();

        /** @var array $array */
        $array = $res->getData();

        $this->assertArraySubset(['description' => 'A document description.'], $array);
    }


}
