<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Connection\Socket;

use Psr\Http\Message\StreamInterface;

interface HandshakeInterface
{
    public function hello(StreamInterface $stream): void;
}
