<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\UnitTest\Connection;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Response\ResponseType;

class ConnectionQueryTest extends ConnectionTestCase
{
    public function testWriteQuery()
    {
        $this->connect();

        $token = 1;
        $message = \Mockery::mock(MessageInterface::class);
        $message->shouldReceive('setOptions')->once();

        $this->stream->shouldReceive('write')->once()->andReturn(20);

        $this->querySerializer->shouldReceive('serialize')->atLeast()->andReturn("['serialized': true]");

        try {
            $this->connection->writeQuery($token, $message);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function testStopQuery()
    {
        $this->connect();

        $token = 1;

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::SUCCESS_ATOM);

        $buffer = new \stdClass();
        $this->catchStreamWrite($buffer);
        $this->catchStreamRead(4 + 8, $buffer);
        $this->catchStreamRead(20, $buffer);

        $this->querySerializer->shouldReceive('serialize')->once()->andReturn("['serialized': true]");

        $this->responseSerializer->shouldReceive('deserialize')->once()->andReturn($response);

        try {
            $this->connection->stopQuery($token);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function testContinueQuery()
    {
        $this->connect();

        $token = 1;

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::SUCCESS_ATOM);

        $buffer = new \stdClass();
        $this->catchStreamWrite($buffer);
        $this->catchStreamRead(4 + 8, $buffer);
        $this->catchStreamRead(20, $buffer);

        $this->querySerializer->shouldReceive('serialize')->once()->andReturn("['serialized': true]");

        $this->responseSerializer->shouldReceive('deserialize')->once()->andReturn($response);

        try {
            $this->connection->continueQuery($token);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }
}
