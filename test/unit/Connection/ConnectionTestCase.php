<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\UnitTest\Connection;

use Mockery;
use Mockery\MockInterface;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\Serializer\SerializerInterface;
use TBolier\RethinkQL\Connection\Connection;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\Socket\HandshakeInterface;
use TBolier\RethinkQL\UnitTest\BaseUnitTestCase;

abstract class ConnectionTestCase extends BaseUnitTestCase
{
    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var MockInterface
     */
    protected $handshake;

    /**
     * @var MockInterface
     */
    protected $querySerializer;

    /**
     * @var MockInterface
     */
    protected $responseSerializer;

    /**
     * @var MockInterface
     */
    protected $stream;

    /**
     * @var callable
     */
    protected $streamWrapper;

    protected function setUp()
    {
        parent::setUp();

        $this->stream = Mockery::mock(StreamInterface::class);
        $this->streamWrapper = function () {
            return $this->stream;
        };

        $this->handshake = Mockery::mock(HandshakeInterface::class);
        $this->querySerializer = Mockery::mock(SerializerInterface::class);
        $this->responseSerializer = Mockery::mock(SerializerInterface::class);

        $this->connection = new Connection(
            $this->streamWrapper,
            $this->handshake,
            'test',
            $this->querySerializer,
            $this->responseSerializer
        );
    }

    /**
     * @return void
     */
    protected function connect(): void
    {
        try {
            $this->handshake->shouldReceive('hello')->once();
            /** @var ConnectionInterface $connection */
            $this->assertInstanceOf(ConnectionInterface::class, $this->connection->connect());
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @param MockInterface $response
     * @return void
     */
    protected function setExpectations($response = null): void
    {
        $this->querySerializer->shouldReceive('serialize')->atLeast()->andReturn("['serialized': true]");

        $buffer = new \stdClass();
        $this->catchStreamWrite($buffer);

        if ($response) {
            $this->catchStreamRead(4 + 8, $buffer);
            $this->catchStreamRead(20, $buffer);

            $this->responseSerializer->shouldReceive('deserialize')->atLeast()->andReturn($response);
        }
    }

    /**
     * @param \stdClass $buffer
     * @return \stdClass
     */
    protected function catchStreamWrite(\stdClass $buffer)
    {
        $this->stream->shouldReceive('write')->andReturnUsing(function ($string) use ($buffer) {
            $buffer->data = $string;

            return 20;
        });

        return $buffer;
    }

    protected function catchStreamRead(int $bytes, \stdClass $buffer): void
    {
        $this->stream->shouldReceive('read')->once()->with($bytes)->andReturnUsing(function ($bytes) use (&$buffer) {
            $data = '';
            if (isset($buffer->data)) {
                $data = substr($buffer->data, 0, $bytes);
            }

            return $data;
        });
    }
}
