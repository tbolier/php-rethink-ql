<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\UnitTest\Connection;

use TBolier\RethinkQL\Connection\Connection;
use TBolier\RethinkQL\Connection\Options;
use TBolier\RethinkQL\Connection\Registry;
use TBolier\RethinkQL\UnitTest\BaseUnitTestCase;

class RegistryTest extends BaseUnitTestCase
{
    /**
     * @return void
     * @throws \Exception
     * @throws \TBolier\RethinkQL\Connection\ConnectionException
     */
    public function testIfRegistryGetsConstructedWithConnections(): void
    {
        $optionsConfig = [
            'dbname' => 'foo',
        ];

        $options = new Options($optionsConfig);

        $options2Config = [
            'dbname' => 'bar',
        ];

        $options2 = new Options($options2Config);

        $registry = new Registry(
            [
                'fooConnection' => $options,
                'barConnection' => $options2,
                'bazConnection' => [],
            ]
        );

        $this->assertTrue($registry->hasConnection('fooConnection'));
        $this->assertTrue($registry->hasConnection('barConnection'));
        $this->assertFalse($registry->hasConnection('bazConnection'));

        $this->assertInstanceOf(Connection::class, $registry->getConnection('fooConnection'));
        $this->assertInstanceOf(Connection::class, $registry->getConnection('barConnection'));
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage The connection fooConnection has already been added
     * @expectedExceptionCode 400
     * @return void
     * @throws \TBolier\RethinkQL\Connection\ConnectionException
     */
    public function testIfExceptionThrownOnDuplicateConnection(): void
    {
        $optionsConfig = [
            'dbname' => 'foo',
        ];

        $options = new Options($optionsConfig);

        $registry = new Registry(
            [
                'fooConnection' => $options,
            ]
        );

        $registry->addConnection('fooConnection', $options);
    }

    /**
     * @expectedException \TBolier\RethinkQL\Connection\ConnectionException
     * @expectedExceptionMessage The connection fooConnection does not exist
     * @expectedExceptionCode 400
     * @return void
     * @throws \TBolier\RethinkQL\Connection\ConnectionException
     */
    public function testIfExceptionThrownOnMissingConnection(): void
    {
        $registry = new Registry([]);

        $registry->getConnection('fooConnection');
    }
}
