<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest;

use Mockery;
use PHPUnit\Framework\TestCase;
use TBolier\RethinkQL\Connection\Connection;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\Options;
use TBolier\RethinkQL\Connection\Socket\Handshake;
use TBolier\RethinkQL\Connection\Socket\Socket;
use TBolier\RethinkQL\Types\VersionDummy\Version;

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
        $options = new Options(PHPUNIT_CONNECTIONS[$name]);

        $this->connection = new Connection(
            function () use ($options) {
                return new Socket(
                    $options
                );
            },
            new Handshake($options->getUser(), $options->getPassword(), Version::V1_0),
            $options->getDbname()
        );

        return $this->connection;
    }

    public function __destruct()
    {
        if ($this->connection->isStreamOpen()) {
            $this->connection->close();
        }

        Mockery::close();
    }
}
