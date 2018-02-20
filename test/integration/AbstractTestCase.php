<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest;

use ArrayObject;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use TBolier\RethinkQL\Connection\Connection;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\Options;
use TBolier\RethinkQL\Connection\Socket\Exception;
use TBolier\RethinkQL\Connection\Socket\Handshake;
use TBolier\RethinkQL\Connection\Socket\Socket;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Rethink;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Serializer\QueryNormalizer;
use TBolier\RethinkQL\Types\VersionDummy\Version;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @var RethinkInterface
     */
    private $r;

    /**
     * @var ConnectionInterface[]
     */
    private $connections = [];

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

        /** @var ResponseInterface $res */
        $res = $this->r->dbList()->run();
        if (\is_array($res->getData()) && !\in_array('test', $res->getData(), true)) {
            $this->r->dbCreate('test')->run();
        }

        return $this->r;
    }

    protected function tearDown()
    {
        if ($this->r === null) {
            return;
        }

        /** @var ResponseInterface $res */
        $res = $this->r->dbList()->run();
        if (\is_array($res->getData()) && \in_array('test', $res->getData(), true)) {
            $this->r->dbDrop('test')->run();
        }
    }

    /**
     * @param string $name
     * @return ConnectionInterface
     * @throws Exception
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
            $options->getDbName(),
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
            $connection->close();
        }

        Mockery::close();
    }

    /**
     * @param $status
     * @param $data
     * @throws \Exception
     */
    protected function assertObStatus($status, $data)
    {
        $res = [];
        $statuses = [
            'tables_created',
            'tables_dropped',
            'created',
            'dropped',
            'renamed',
            'unchanged',
            'skipped',
            'replaced',
            'inserted',
            'errors',
            'deleted',
        ];
        $data = new ArrayObject($data);

        foreach ($statuses as $s) {
            $status[$s] = $status[$s] ?? 0;
        }

        $data->setFlags($data::ARRAY_AS_PROPS);

        foreach ($statuses as $s) {
            $res[$s] = $data[$s] ?? 0;
        }

        $this->assertEquals($status, $res);
    }
}
