<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection\Socket;

use Psr\Http\Message\StreamInterface;

interface HandshakeInterface
{
    /**
     * @param StreamInterface $stream
     * @return void
     */
    public function hello(StreamInterface $stream): void;
}
