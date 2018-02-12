<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\UnitTest\Connection;

use TBolier\RethinkQL\Connection\Options;
use TBolier\RethinkQL\UnitTest\BaseUnitTestCase;

class OptionsTest extends BaseUnitTestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testAccessors(): void
    {
        $config = [
            'hostname' => 'example.test',
            'port' => 42,
            'dbname' => 'test',
            'user' => 'admin',
            'password' => 'secret',
            'timeout' => 120,
            'timeout_stream' => 300,
            'ssl' => true,
        ];

        $options = new Options($config);

        $this->assertEquals($config['hostname'], $options->getHostname());
        $this->assertEquals($config['port'], $options->getPort());
        $this->assertEquals($config['dbname'], $options->getDbName());
        $this->assertEquals($config['user'], $options->getUser());
        $this->assertEquals($config['password'], $options->getPassword());
        $this->assertEquals($config['timeout'], $options->getTimeout());
        $this->assertEquals($config['timeout_stream'], $options->getTimeoutStream());
        $this->assertEquals($config['ssl'], $options->isSsl());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testIfHasDefaultDatabaseReturnsTrue(): void
    {
        $config = [
            'dbname' => 'test',
        ];

        $options = new Options($config);

        $this->assertTrue($options->hasDefaultDatabase());
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testIfHasDefaultDatabaseReturnsFalse(): void
    {
        $config = [];

        $options = new Options($config);

        $this->assertFalse($options->hasDefaultDatabase());
    }
}
