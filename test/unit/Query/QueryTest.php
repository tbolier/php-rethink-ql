<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\UnitTest\Query;

use PHPUnit\Framework\TestCase;
use TBolier\RethinkQL\Query\Query;

class QueryTest extends TestCase
{
    /**
     * @rreturn void
     */
    public function testGetQuery(): void
    {
        $queryArray = ['foo' => 'bar'];
        $query = new Query($queryArray);

        $this->assertEquals($queryArray, $query->getQuery());
    }

    /**
     * @return void
     */
    public function testJsonSerialize(): void
    {
        $queryArray = ['foo' => 'bar'];
        $query = new Query($queryArray);

        $this->assertEquals($queryArray, $query->jsonSerialize());
    }
}
