<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Operation;

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
     * @return void
     * @throws \Exception
     */
    public function testFilterAndIsEmpty(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['id' => 1])
            ->isEmpty()
            ->run();

        $this->assertFalse($res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndCount(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->count()
            ->run();

        $this->assertEquals(2, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndAvg(): void
    {
        $this->insertDocumentWithNumber(1, 50);
        $this->insertDocumentWithNumber(2, 100);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->avg('number')
            ->run();

        $this->assertEquals(75, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndSum(): void
    {
        $this->insertDocumentWithNumber(1, 50);
        $this->insertDocumentWithNumber(2, 100);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->sum('number')
            ->run();

        $this->assertEquals(150, $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndLimit(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->limit(1)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndSkip(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $cursor = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->skip(1)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndOrderBy(): void
    {
        $this->insertDocument(1);
        $this->insertDocument('stringId');

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->orderBy($this->r()->desc('id'))
            ->run();

        $this->assertArraySubset(['id' => 'stringId'], $res->getData()[0]);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndMin(): void
    {
        $this->insertDocumentWithNumber(1, 77);
        $this->insertDocumentWithNumber(2, 99);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->min('number')
            ->run();

        $this->assertArraySubset(['number' => 77], $res->getData());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testFilterAndMax(): void
    {
        $this->insertDocumentWithNumber(1, 77);
        $this->insertDocumentWithNumber(2, 99);

        /** @var ResponseInterface $res */
        $res = $this->r()
            ->table('tabletest')
            ->filter(['description' => 'A document description.'])
            ->max('number')
            ->run();

        $this->assertArraySubset(['number' => 99], $res->getData());
    }

    /**
     * @throws \Exception
     */
    public function testFilterWithDateGreaterThanLogic(): void
    {
        $this->insertDocumentWithDate(1, new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $row = $this->r()->row('date')->gt((new \DateTime('now'))->format(\DateTime::ATOM));
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testFilterWithDateLowerThanLogic(): void
    {
        $this->insertDocumentWithDate(1, new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $row = $this->r()->row('date')->lt((new \DateTime('now'))->format(\DateTime::ATOM));
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testFilterGreaterOrEqualThanLogic(): void
    {
        $this->insertDocumentWithNumber(1, 11);
        $this->insertDocumentWithNumber(2, 22);
        $this->insertDocumentWithNumber(3, 33);

        /** @var ResponseInterface $res */
        $row = $this->r()->row('number')->ge(22);
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(2, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testFilterLowerOrEqualThanLogic(): void
    {
        $this->insertDocumentWithNumber(1, 11);
        $this->insertDocumentWithNumber(2, 22);
        $this->insertDocumentWithNumber(3, 33);

        /** @var ResponseInterface $res */
        $row = $this->r()->row('number')->le(22);
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(2, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testFilterWithDateEqualLogic(): void
    {
        $this->insertDocumentWithDate(1, $yesterday = new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $row = $this->r()->row('date')->eq($yesterday->format(\DateTime::ATOM));
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testFilterWithDateNotEqualLogic(): void
    {
        $this->insertDocumentWithDate(1, $yesterday = new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, new \DateTime('+1 days'));

        $row = $this->r()->row('date')->ne($yesterday->format(\DateTime::ATOM));
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testFilterWithDateMultipleAndLogic(): void
    {
        $this->insertDocumentWithDate(1, $yesterday = new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, $tomorrow = new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $row = $this->r()->row('date')->eq($yesterday->format(\DateTime::ATOM))->and(
            $this->r()->row('date')->ne($tomorrow->format(\DateTime::ATOM))->and(
                $this->r()->row('id')->eq(1)->and(
                    $this->r()->row('id')->ne(2)
                )
            )
        );

        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(1, $cursor);
    }

    /**
     * @throws \Exception
     */
    public function testFilterWithDateMultipleOrLogic(): void
    {
        $this->insertDocumentWithDate(1, $yesterday = new \DateTime('-1 days'));
        $this->insertDocumentWithDate(2, $tomorrow = new \DateTime('+1 days'));

        /** @var ResponseInterface $res */
        $row = $this->r()->row('date')->eq($yesterday->format(\DateTime::ATOM))->or(
            $this->r()->row('date')->eq($tomorrow->format(\DateTime::ATOM))->and(
                $this->r()->row('id')->gt(0)->or(
                    $this->r()->row('id')->lt(3)
                )
            )
        );

        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(2, $cursor);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testNotLogic(): void
    {
        $this->insertDocumentWithNumber(1, 55);
        $this->insertDocumentWithNumber(2, 77);
        $this->insertDocumentWithNumber(3, 99);
        $this->insertDocumentWithNumber(4, 111);

        $row = $this->r()->row('number')->eq(77)->not();
        $cursor = $this->r()
            ->table('tabletest')
            ->filter($row)
            ->run();

        $this->assertCount(3, $cursor);
    }
}
