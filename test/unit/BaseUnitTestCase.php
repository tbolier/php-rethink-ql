<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\UnitTest;

use Mockery;
use PHPUnit\Framework\TestCase;

class BaseUnitTestCase extends TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }

    protected function setUp()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        parent::setUp();
    }
}
