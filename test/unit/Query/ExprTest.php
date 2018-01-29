<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\UnitTest\Query;

use PHPUnit\Framework\TestCase;
use TBolier\RethinkQL\Query\Expr;

class ExprTest extends TestCase
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
