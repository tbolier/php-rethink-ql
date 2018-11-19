<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\UnitTest\Connection;

use Exception;
use TBolier\RethinkQL\Connection\ConnectionException;
use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Response\Cursor;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Response\ResponseType;

class ConnectionTest extends ConnectionTestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage Test exception
     * @return void
     * @throws ConnectionException
     */
    public function testConnectThrowsCorrectException(): void
    {
        $this->handshake->shouldReceive('hello')->once()->andThrow(
            new ConnectionException('Test exception')
        );

        $this->connection->connect();
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage No open stream, please connect first
     */
    public function testQueryWithoutConnection(): void
    {
        $this->connection->writeQuery(1223456789, \Mockery::mock(MessageInterface::class));
    }

    public function testExpr()
    {
        $this->connect();

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::SUCCESS_ATOM);

        $this->setExpectations($response);

        $this->connection->expr('foo');
    }

    public function testRunAtom()
    {
        $this->connect();

        $message = \Mockery::mock(MessageInterface::class);
        $message->shouldReceive('setOptions')->once()->andReturn();

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::SUCCESS_ATOM);

        $this->setExpectations($response);

        try {
            $this->assertInstanceOf(ResponseInterface::class, $this->connection->run($message, false));
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function testRunPartial()
    {
        $this->connect();

        $message = \Mockery::mock(MessageInterface::class);
        $message->shouldReceive('setOptions')->once()->andReturn();

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::SUCCESS_PARTIAL);
        $response->shouldReceive('getData')->atLeast()->andReturn(['yolo']);
        $response->shouldReceive('isAtomic')->once()->andReturn(true);

        $this->setExpectations($response);

        try {
            $this->assertInstanceOf(Cursor::class, $this->connection->run($message, false));
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function testRunSequence()
    {
        $this->connect();

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::SUCCESS_SEQUENCE);
        $response->shouldReceive('getData')->atLeast()->andReturn(['yolo']);
        $response->shouldReceive('isAtomic')->once()->andReturn(true);

        $this->setExpectations($response);

        $message = \Mockery::mock(MessageInterface::class);
        $message->shouldReceive('setOptions')->once()->andReturn();

        try {
            $this->assertInstanceOf(Cursor::class, $this->connection->run($message, false));
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function testServer(): void
    {
        $this->connect();

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::SERVER_INFO);
        $response->shouldReceive('getData')->atLeast()->andReturn(['yolo']);

        $this->setExpectations($response);

        $res = $this->connection->server();

        $this->assertEquals(QueryType::SERVER_INFO, $res->getType());
        $this->assertInternalType('array', $res->getData());
        $this->assertEquals(['yolo'], $res->getData());
    }

    /**
     * @throws Exception
     */
    public function testRunNoReply()
    {
        $this->connect();

        $message = \Mockery::mock(MessageInterface::class);
        $message->shouldReceive('setOptions')->once()->andReturn();

        $this->setExpectations();

        try {
            $this->assertEmpty($this->connection->runNoReply($message));
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function testClose(): void
    {
        $this->connect();

        $this->stream->shouldReceive('close')->once();

        try {
            $this->connection->close(false);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testCloseNoReplyWait(): void
    {
        $this->connect();

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::WAIT_COMPLETE);

        $this->setExpectations($response);

        $this->stream->shouldReceive('close')->once();

        try {
            $this->connection->close(true);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }

    /**
     * @throws Exception
     */
    public function testUse()
    {
        try {
            $this->connection->use('test');
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }
}
