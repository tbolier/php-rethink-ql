<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest\Connection;

use Mockery;
use Mockery\MockInterface;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\OptionsInterface;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;
use TBolier\RethinkQL\Types\Query\QueryType;

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

        $this->assertInternalType('array', $connection->expr('foo'));
    }

    /**
     * @throws \Exception
     */
    public function testServer()
    {
        /** @var ConnectionInterface $connection */
        $res = $this->createConnection('phpunit_default')->connect()->server();

        $this->assertEquals(QueryType::SERVER_INFO, $res['t']);
    }
}
