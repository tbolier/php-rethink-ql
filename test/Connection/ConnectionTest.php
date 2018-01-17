<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Test\Connection;

use Mockery;
use Mockery\MockInterface;
use TBolier\RethinkQL\Connection\OptionsInterface;
use TBolier\RethinkQL\Test\BaseTestCase;

class ConnectionTest extends BaseTestCase
{
    /**
     * @var MockInterface
     */
    private $optionsMock;

    public function setUp()
    {
        parent::setUp();

        $this->optionsMock = Mockery::mock(OptionsInterface::class);
    }

    public function testConnect()
    {
        $this->assertTrue(\is_array($this->createConnection('phpunit_default')->connect()->execute()));
    }
}
