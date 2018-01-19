<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Test\Connection;

use Mockery;
use Mockery\MockInterface;
use TBolier\RethinkQL\Connection\ConnectionInterface;
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

    /**
     * @throws \Exception
     */
    public function testConnect()
    {
        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();

        static::assertInternalType('array', $connection->expr('foo'));
    }

    public function testServer()
    {
        /** @var ConnectionInterface $connection */
        $res = $this->createConnection('phpunit_default')->connect()->server();

        $this->assertEquals(QueryType::SERVER_INFO, $res['t']);
    }
}
