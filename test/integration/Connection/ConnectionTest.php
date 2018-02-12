<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\IntegrationTest\Connection;

use Mockery;
use Mockery\MockInterface;
use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\OptionsInterface;
use TBolier\RethinkQL\IntegrationTest\AbstractTestCase;
use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\Types\Query\QueryType;

class ConnectionTest extends AbstractTestCase
{
    /**
     * @var MockInterface
     */
    private $optionsMock;

    public function setUp()
    {
        parent::setUp();

        $this->optionsMock = Mockery::mock(OptionsInterface::class);
    }

    /**
     * @throws \Exception
     */
    public function testConnect()
    {
        /** @var ConnectionInterface $connection */
        $connection = $this->createConnection('phpunit_default')->connect();
        $result = $connection->expr('foo');

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals('foo', $result->getData());
    }

    /**
     * @throws \Exception
     */
    public function testServer()
    {
        /** @var ConnectionInterface $connection */
        $res = $this->createConnection('phpunit_default')->connect()->server();

        $this->assertEquals(QueryType::SERVER_INFO, $res->getType());
        $this->assertInternalType('string', $res->getData()['name']);
    }
}
