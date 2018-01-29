<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\UnitTest\Query;

use PHPUnit\Framework\TestCase;
use TBolier\RethinkQL\Query\Query;

class QueryTest extends TestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testGetQuery(): void
    {
        $queryArray = ['foo' => 'bar'];
        $query = new Query($queryArray);

        $this->assertEquals($queryArray, $query->getQuery());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testJsonSerialize(): void
    {
        $queryArray = ['foo' => 'bar'];
        $query = new Query($queryArray);

        $this->assertEquals($queryArray, $query->jsonSerialize());
    }
}
