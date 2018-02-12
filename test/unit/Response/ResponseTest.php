<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\UnitTest\Query;

use TBolier\RethinkQL\Response\Response;
use TBolier\RethinkQL\UnitTest\BaseUnitTestCase;

class ResponseTest extends BaseUnitTestCase
{
    /**
     * @return void
     * @throws \Exception
     */
    public function testAccessors(): void
    {
        $type = 1;
        $data = ['foo' => 'bar'];
        $backtrace = [0 => [1 => []]];
        $profile = [2 => [3 => []]];
        $note = [4 => [5 => []]];

        $response = new Response(
            $type,
            $data,
            $backtrace,
            $profile,
            $note
        );

        $this->assertEquals($type, $response->getType());
        $this->assertEquals($data, $response->getData());
        $this->assertEquals($backtrace, $response->getBacktrace());
        $this->assertEquals($profile, $response->getProfile());
        $this->assertEquals($note, $response->getNote());
    }
}
