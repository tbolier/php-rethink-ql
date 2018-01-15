<?php
namespace TBolier\RethinkConnect\Test;

use Mockery;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function setUp()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        parent::setUp();
    }

    public function __destruct()
    {
        Mockery::close();
    }
}
