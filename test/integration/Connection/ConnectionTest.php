<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use Exception;
use Mockery;
use Mockery\MockInterface;
use TBolier\RethinkQL\Connection\Socket\Socket;
use TBolier\RethinkQL\IntegrationTest\BaseTestCase;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Response\ResponseType;

function unpack($format = '', $data = '', $offset = 0)
{
    return ConnectionTest::$functions->unpack($format, $data, $offset);
}

function random_int($min = 0, $max = 0)
{
    return ConnectionTest::$functions->random_int($min, $max);
}

class ConnectionTest extends BaseTestCase
{
    /**
     * @var MockInterface
     */
    public static $functions;

    /**
     * @var MockInterface
     */
    private $optionsMock;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        self::$functions = Mockery::mock();

        $this->useDefaultInternals();

        $this->optionsMock = Mockery::mock(OptionsInterface::class);
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function testConnect(): void
    {
        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();
        $result = $connection->expr('foo');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals('foo', $result->getData()[0]);
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function testConnectWithOpenConnection(): void
    {
        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();
        $connection2 = $connection->connect();

        $this->assertSame($connection, $connection2);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Test exception
     * @return void
     */
    public function testConnectThrowsCorrectException(): void
    {
        $options = new Options(PHPUNIT_CONNECTIONS['phpunit_default']);

        $handshakeMock = Mockery::mock('\TBolier\RethinkQL\Connection\Socket\HandshakeInterface');
        $handshakeMock->shouldReceive('hello')->andThrow(new \Exception('Test exception', 404));

        $serializerMock = Mockery::mock('\Symfony\Component\Serializer\SerializerInterface');

        $connection = new Connection(
            function () use ($options) {
                return new Socket(
                    $options
                );
            },
            $handshakeMock,
            $options->getDbName(),
            $serializerMock,
            $serializerMock
        );

        $connection->connect();
    }

    /**
     * @return void
     */
    public function testServer(): void
    {
        /** @var ConnectionInterface $connection */
        $res = $this->createConnection('phpunit_default')->connect()->server();

        $this->assertEquals(QueryType::SERVER_INFO, $res->getType());
        $this->assertInternalType('string', $res->getData()[0]['name']);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Not connected
     */
    public function testServerThrowsNotConnectedException(): void
    {
        /** @var ConnectionInterface $connection */
        $this->createConnection('phpunit_default')->server();
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     */
    public function testServerThrowsExceptionOnConnectionException(): void
    {
        $options = new Options(PHPUNIT_CONNECTIONS['phpunit_default']);

        $handshakeMock = Mockery::mock('\TBolier\RethinkQL\Connection\Socket\HandshakeInterface');
        $handshakeMock->shouldReceive('hello');

        $serializerMock = Mockery::mock('\Symfony\Component\Serializer\SerializerInterface');

        $connection = new Connection(
            function () use ($options) {
                return new Socket(
                    $options
                );
            },
            $handshakeMock,
            $options->getDbName(),
            $serializerMock,
            $serializerMock
        );

        /** @var ConnectionInterface $connection */
        $connection->connect()->server();
    }

    /**
     * @return void
     */
    public function testClose(): void
    {
        $connection = $this->createConnection('phpunit_default')->connect();

        $this->assertTrue($connection->isStreamOpen());

        $connection->close(false);

        $this->assertFalse($connection->isStreamOpen());
    }

    /**
     * @return void
     */
    public function testCloseNoReplyWait(): void
    {
        $connection = $this->createConnection('phpunit_default')->connect();

        $this->assertTrue($connection->isStreamOpen());

        $connection->close(true);

        $this->assertFalse($connection->isStreamOpen());
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Not connected
     * @return void
     */
    public function testCloseThrowsExceptionOnNotConnected(): void
    {
        $connection = $this->createConnection('phpunit_default');
        $connection->close();
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Not connected
     * @return void
     */
    public function testRunThrowsExceptionOnNotConnected(): void
    {
        $connection = $this->createConnection('phpunit_default');
        $connection->run(Mockery::mock('\TBolier\RethinkQL\Query\MessageInterface'));
    }

    /**
     * @return void
     */
    public function testRunNoReply(): void
    {
        $message = $this->setUpMessageMock();

        $connection = $this->createConnection('phpunit_default')->connect();

        $reply = $connection->runNoReply($message);

        $connection->close(false);

        $this->assertNull($reply);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Generating the token failed.
     * @return void
     */
    public function testNoReplyException(): void
    {
        self::$functions->shouldReceive('random_int')->andThrow(Exception::class, 'foo')->byDefault();

        $connection = $this->createConnection('phpunit_default')->connect();

        $connection->close(true);
    }

    /**
     * @return void
     */
    public function testRunPartialSuccess(): void
    {
        $expectedToken = [
            'token' => 1,
            'token2' => 0,
            'size' => 19
        ];


        self::$functions->shouldReceive('unpack')->with('Vtoken/Vtoken2/Vsize', '', 0)->andReturn($expectedToken);
        self::$functions->shouldReceive('random_int')->andReturn(1);

        $message = $this->setUpMessageMock();

        $responseMock = Mockery::mock('\TBolier\RethinkQL\Response\ResponseInterface');
        $responseMock->shouldReceive('getType')->andReturn(ResponseType::SUCCESS_PARTIAL);
        $responseMock->shouldReceive('getData')->andReturn([]);

        $connection = $this->setUpMockConnection($responseMock);

        $connection = $connection->connect();

        /** @var Cursor $reply */
        $reply = $connection->run($message);

        $connection->close(false);

        $this->assertInstanceOf(Cursor::class, $reply);
    }


    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Serializing query message failed.
     * @return void
     */
    public function testIfRunThrowsException(): void
    {
        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();

        $messageMock = Mockery::mock('\TBolier\RethinkQL\Query\Message');
        $messageMock->shouldReceive('setOptions');

        $connection->run($messageMock);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Generating the token failed.
     * @return void
     */
    public function testIfGenerateTokenThrowsException(): void
    {
        self::$functions = Mockery::mock();

        self::$functions->shouldReceive('random_int')->andThrow(Exception::class, 'foo')->byDefault();

        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();

        $messageMock = Mockery::mock('\TBolier\RethinkQL\Query\Message');
        $messageMock->shouldReceive('setOptions');

        $connection->run($messageMock);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Response message has no type.
     * @return void
     */
    public function testConnectionExceptionThrownOnNoResponseType(): void
    {
        $expectedToken = [
            'token' => 1,
            'token2' => 0,
            'size' => 19
        ];

        self::$functions->shouldReceive('unpack')->with('Vtoken/Vtoken2/Vsize', '', 0)->andReturn($expectedToken);
        self::$functions->shouldReceive('random_int')->andReturn(1);

        $message = $this->setUpMessageMock();

        $responseMock = Mockery::mock('\TBolier\RethinkQL\Response\ResponseInterface');
        $responseMock->shouldReceive('getType')->andReturn(0);

        $connection = $this->setUpMockConnection($responseMock);
        $connection = $connection->connect();

        $reply = $connection->run($message);

        $connection->close(false);

        $this->assertEquals($responseMock, $reply);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Compile error: foo, jsonQuery: "{}"
     * @return void
     */
    public function testConnectionExceptionThrownOnCompileError(): void
    {
        $expectedToken = [
            'token' => 1,
            'token2' => 0,
            'size' => 19
        ];

        self::$functions->shouldReceive('unpack')->with('Vtoken/Vtoken2/Vsize', '', 0)->andReturn($expectedToken);
        self::$functions->shouldReceive('random_int')->andReturn(1);

        $message = $this->setUpMessageMock();

        $responseMock = Mockery::mock('\TBolier\RethinkQL\Response\ResponseInterface');
        $responseMock->shouldReceive('getType')->andReturn(17);
        $responseMock->shouldReceive('getData')->andReturn([0=>'foo']);

        $connection = $this->setUpMockConnection($responseMock);
        $connection = $connection->connect();

        $reply = $connection->run($message);

        $connection->close(false);

        $this->assertEquals($responseMock, $reply);
    }


    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Runtime error: foo, jsonQuery: "{}"
     * @return void
     */
    public function testConnectionExceptionThrownOnRuntimeError(): void
    {
        $expectedToken = [
            'token' => 1,
            'token2' => 0,
            'size' => 19
        ];

        self::$functions->shouldReceive('unpack')->with('Vtoken/Vtoken2/Vsize', '', 0)->andReturn($expectedToken);
        self::$functions->shouldReceive('random_int')->andReturn(1);

        $message = $this->setUpMessageMock();

        $responseMock = Mockery::mock('\TBolier\RethinkQL\Response\ResponseInterface');
        $responseMock->shouldReceive('getType')->andReturn(18);
        $responseMock->shouldReceive('getData')->andReturn([0=>'foo']);

        $connection = $this->setUpMockConnection($responseMock);
        $connection = $connection->connect();

        $reply = $connection->run($message);

        $connection->close(false);

        $this->assertEquals($responseMock, $reply);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Received wrong token. Response does not match the request. Expected 1, received 11
     * @return void
     */
    public function testConnectionExceptionThrownOnInvalidResponseToken(): void
    {
        $expectedToken = [
            'token' => 11,
            'token2' => 0,
            'size' => 19
        ];

        self::$functions->shouldReceive('unpack')->with('Vtoken/Vtoken2/Vsize', '', 0)->andReturn($expectedToken);
        self::$functions->shouldReceive('random_int')->andReturn(1);

        $message = $this->setUpMessageMock();

        $responseMock = Mockery::mock('\TBolier\RethinkQL\Response\ResponseInterface');
        $responseMock->shouldReceive('getType')->andReturn(18);
        $responseMock->shouldReceive('getData')->andReturn([0=>'foo']);

        $connection = $this->setUpMockConnection($responseMock);
        $connection = $connection->connect();

        $reply = $connection->run($message);

        $connection->close(false);

        $this->assertEquals($responseMock, $reply);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Invalid response from server: Invalid token.
     * @return void
     */
    public function testConnectionExceptionThrownOnInvalidResponseToken2(): void
    {
        $expectedToken = [
            'token' => 1,
            'token2' => 42,
            'size' => 19
        ];

        self::$functions->shouldReceive('unpack')->with('Vtoken/Vtoken2/Vsize', '', 0)->andReturn($expectedToken);
        self::$functions->shouldReceive('random_int')->andReturn(1);

        $message = $this->setUpMessageMock();

        $responseMock = Mockery::mock('\TBolier\RethinkQL\Response\ResponseInterface');
        $responseMock->shouldReceive('getType')->andReturn(18);
        $responseMock->shouldReceive('getData')->andReturn([0=>'foo']);

        $connection = $this->setUpMockConnection($responseMock);
        $connection = $connection->connect();

        $reply = $connection->run($message);

        $connection->close(false);

        $this->assertEquals($responseMock, $reply);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Server says PHP-RQL is buggy:
     * @return void
     */
    public function testConnectionExceptionThrownOnClientErrorResponseType(): void
    {
        $expectedToken = [
            'token' => 1,
            'token2' => 0,
            'size' => 19
        ];

        self::$functions->shouldReceive('unpack')->with('Vtoken/Vtoken2/Vsize', '', 0)->andReturn($expectedToken);
        self::$functions->shouldReceive('random_int')->andReturn(1);

        $message = $this->setUpMessageMock();

        $responseMock = Mockery::mock('\TBolier\RethinkQL\Response\ResponseInterface');
        $responseMock->shouldReceive('getType')->andReturn(16);
        $responseMock->shouldReceive('getData')->andReturn([0=>'foo']);

        $connection = $this->setUpMockConnection($responseMock);
        $connection = $connection->connect();

        $reply = $connection->run($message);

        $connection->close(false);

        $this->assertEquals($responseMock, $reply);
    }

    /**
     * @return void
     */
    public function testUse(): void
    {
        $connection = $this->createConnection('phpunit_default');
        $connection->use('foo');

        $reflection = new \ReflectionClass($connection);
        $dbName = $reflection->getProperty('dbName');
        $dbName->setAccessible(true);
        $this->assertEquals('foo', $dbName->getValue($connection));}

    /**
     * Resets the mocks to their PHP internal equivalents
     */
    private function useDefaultInternals(): void
    {
        // Reset expectations to use PHP built-in functions
        self::$functions = Mockery::mock();

        self::$functions->shouldReceive('random_int')->byDefault()->andReturnUsing(function ($min = 0, $max = 0) {
            return \random_int($min, $max);
        });

        self::$functions->shouldReceive('unpack')->byDefault()->andReturnUsing(function ($format = '', $data = '', $offset = 0) {
            return \unpack($format, $data, $offset);
        });
    }

    /**
     * @return MockInterface
     */
    private function setUpMessageMock(): MockInterface
    {
        $message = Mockery::mock('\TBolier\RethinkQL\Query\MessageInterface');
        $message->shouldReceive('setOptions')->andReturnSelf()->getMock();
        $message->shouldReceive('jsonSerialize')->andReturn('{}')->getMock();

        return $message;
    }

    /**
     * @return ConnectionInterface
     */
    private function setUpMockConnection(MockInterface $responseMock): ConnectionInterface
    {
        $handshakeMock = Mockery::mock('\TBolier\RethinkQL\Connection\Socket\HandshakeInterface');
        $handshakeMock->shouldReceive('hello')->andReturnSelf();

        $serializerMock = Mockery::mock('\Symfony\Component\Serializer\SerializerInterface');
        $serializerMock->shouldReceive('serialize')->andReturn('{}');

        $serializerMock->shouldReceive('deserialize')->andReturn($responseMock);

        $streamMock = Mockery::mock('\TBolier\RethinkQL\Connection\Socket\Socket');
        $streamMock->shouldReceive('isWritable')->andReturn(true);
        $streamMock->shouldReceive('write')->andReturn(1);
        $streamMock->shouldReceive('close')->andReturn(1);
        $streamMock->shouldReceive('read')->andReturn('');

        $options = new Options(PHPUNIT_CONNECTIONS['phpunit_default']);

        $connection = new Connection(
            function () use ($options, $streamMock) {
                return $streamMock;
            },
            $handshakeMock,
            $options->getDbName(),
            $serializerMock,
            $serializerMock
        );


        return $connection;
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $this->useDefaultInternals();

        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}
