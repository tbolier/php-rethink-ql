<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\UnitTest\Query;

use TBolier\RethinkQL\Query\Expr;
use TBolier\RethinkQL\UnitTest\BaseUnitTestCase;

class ExprTest extends BaseUnitTestCase
{
    /**
     * @var Expr
     */
    private $expr;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->expr = new Expr('foo');
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testGetQuery(): void
    {
        $this->assertEquals('foo', $this->expr->getQuery());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testJsonSerialize(): void
    {
        $this->assertEquals('foo', $this->expr->jsonSerialize());
    }
}
