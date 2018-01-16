<?php
declare(strict_types=1);

namespace TBolier\RethinkConnect\Test\Connection;

use Mockery;
use TBolier\RethinkConnect\Connection\Connection;
use TBolier\RethinkConnect\Connection\ConnectionInterface;
use TBolier\RethinkConnect\Connection\OptionsInterface;
use TBolier\RethinkConnect\Test\BaseTestCase;

class ConnectionTest extends BaseTestCase
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    public function setUp()
    {
        parent::setUp();

        /** @var  OptionsInterface $optionsMock */
        $optionsMock = Mockery::mock(OptionsInterface::class);

        $this->connection = new Connection($optionsMock);
    }

    public function testConnect()
    {
        $this->connection->connect();
    }
}
