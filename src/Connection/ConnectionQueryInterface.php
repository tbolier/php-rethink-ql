<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Connection;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface ConnectionQueryInterface
{
    /**
     * @param int $token
     * @param MessageInterface $message
     * @return int
     */
    public function writeQuery(int $token, MessageInterface $message): int;

    /**
     * @param int $token
     * @return ResponseInterface
     */
    public function continueQuery(int $token): ResponseInterface;

    /**
     * @param int $token
     * @return ResponseInterface
     */
    public function stopQuery(int $token): ResponseInterface;
}
