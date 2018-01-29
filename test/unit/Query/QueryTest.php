<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\UnitTest\Query;

use TBolier\RethinkQL\Query\Query;
use TBolier\RethinkQL\UnitTest\BaseUnitTestCase;

class QueryTest extends BaseUnitTestCase
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
