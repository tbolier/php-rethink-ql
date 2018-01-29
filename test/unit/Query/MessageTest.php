<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\UnitTest\Query;

use PHPUnit\Framework\TestCase;
use TBolier\RethinkQL\Query\Message;
use TBolier\RethinkQL\Query\Options;
use TBolier\RethinkQL\Query\Query;

class MessageTest extends TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testAccessors(): void
    {
        $queryType = 1;
        $query = new Query([]);
        $options = new Options();

        $message = new Message();

        $message->setQueryType($queryType);
        $message->setQuery($query);
        $message->setOptions($options);

        $this->assertEquals(1, $message->getQueryType());
        $this->assertEquals($query, $message->getQuery());
        $this->assertEquals($options, $message->getOptions());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testToArray(): void
    {
        $queryType = 1;
        $query = new Query([]);
        $options = new Options();

        $message = new Message($queryType, $query, $options);

        $expectedResults = [
            1,
            $query,
            (object) $options
        ];

        $this->assertEquals($expectedResults, $message->toArray());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testJsonSerialize(): void
    {
        $queryType = 1;
        $query = new Query([]);
        $options = new Options();

        $message = new Message($queryType, $query, $options);

        $expectedResults = [
            1,
            $query,
            (object) $options
        ];

        $this->assertEquals($expectedResults, $message->jsonSerialize());
    }
}
