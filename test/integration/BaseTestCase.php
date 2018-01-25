<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\IntegrationTest;

use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TBolier\RethinkQL\Connection\Connection;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\Options;
use TBolier\RethinkQL\Connection\Socket\Handshake;
use TBolier\RethinkQL\Connection\Socket\Socket;
use TBolier\RethinkQL\Rethink;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Serializer\QueryNormalizer;
use TBolier\RethinkQL\Types\VersionDummy\Version;

class BaseTestCase extends TestCase
{
    /**
     * @var RethinkInterface
     */
    private $r;

    /**
     * @var ConnectionInterface[]
     */
    private $connections;

    protected function setUp()
    {
        Mockery::getConfiguration()->allowMockingNonExistentMethods(false);

        parent::setUp();
    }

    protected function r()
    {
        if ($this->r !== null) {
            return $this->r;
        }

        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();
        $connection->connect()->use('test');

        $this->r = new Rethink($connection);

        if (!\in_array('test', $this->r->dbList()->run()->getData()[0])) {
            $this->r->dbCreate('test')->run();
        }

        return $this->r;
    }

    protected function tearDown()
    {
        if ($this->r !== null && $this->r->connection()->isStreamOpen() && \in_array('test',
                $this->r->dbList()->run()->getData()[0], true)) {
            $this->r->dbDrop('test')->run();
        }
    }

    /**
     * @param string $name
     * @return ConnectionInterface
     */
    protected function createConnection(string $name): ConnectionInterface
    {
        $options = new Options(PHPUNIT_CONNECTIONS[$name]);

        $connection = new Connection(
            function () use ($options) {
                return new Socket(
                    $options
                );
            },
            new Handshake($options->getUser(), $options->getPassword(), Version::V1_0),
            $options->getDbname(),
            new Serializer(
                [new QueryNormalizer()],
                [new JsonEncoder()]
            ),
            new Serializer(
                [new ObjectNormalizer()],
                [new JsonEncoder()]
            )
        );

        $this->connections[] = $connection;

        return $connection;
    }

    public function __destruct()
    {
        foreach ($this->connections as $connection) {
            if ($connection->isStreamOpen()) {
                $connection->close();
            }
        }

        Mockery::close();
    }
}
