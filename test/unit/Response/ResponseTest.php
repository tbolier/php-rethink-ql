<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\UnitTest\Query;

use PHPUnit\Framework\TestCase;
use TBolier\RethinkQL\Response\Response;

class ResponseTest extends TestCase
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
