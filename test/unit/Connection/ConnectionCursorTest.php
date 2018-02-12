<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\UnitTest\Connection;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Types\Response\ResponseType;

class ConnectionCursorTest extends ConnectionTestCase
{
    public function testRewindFromCursor()
    {
        $this->connect();

        $message = \Mockery::mock(MessageInterface::class);
        $message->shouldReceive('setOptions')->once();

        $response = \Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getType')->atLeast()->andReturn(ResponseType::SUCCESS_ATOM);

        $this->setExpectations($response);

        try {
            $this->connection->rewindFromCursor($message);
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }

        $this->assertTrue(true);
    }
}
