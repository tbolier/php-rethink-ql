<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Test;

use Mockery;
use PHPUnit\Framework\TestCase;
use TBolier\RethinkQL\Connection\Connection;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\Options;

class BaseTestCase extends TestCase
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    protected function setUp()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        parent::setUp();
    }

    /**
     * @param string $name
     * @return ConnectionInterface
     */
    protected function createConnection(string $name): ConnectionInterface
    {
        $this->connection = new Connection(new Options(PHPUNIT_CONNECTIONS[$name]));

        return $this->connection;
    }

    public function __destruct()
    {
        if ($this->connection->isConnected()) {
            $this->connection->close();
        }

        Mockery::close();
    }
}
