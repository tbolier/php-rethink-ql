<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Connection;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface ConnectionCursorInterface extends ConnectionQueryInterface
{
    /**
     * @param MessageInterface $message
     * @return ResponseInterface
     * @throws ConnectionException
     */
    public function rewindFromCursor(MessageInterface $message): ResponseInterface;
}
