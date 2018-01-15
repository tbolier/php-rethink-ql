<?php
namespace TBolier\RethinkConnect\Test\Connection;

use Mockery;
use TBolier\RethinkConnect\Connection\Connection;
use TBolier\RethinkConnect\Connection\OptionsInterface;
use TBolier\RethinkConnect\Test\BaseTestCase;

class ConnectionTest extends BaseTestCase
{
    /**
     * @var \TBolier\RethinkConnect\Connection\ConnectionInterface
     */
    private $connection;

    public function setUp()
    {
        parent::setUp();

        $optionsMock = Mockery::mock(OptionsInterface::class);

        $this->connection = new Connection($optionsMock);
    }

    public function testConnect()
    {
        $this->connection->connect();
    }
}
