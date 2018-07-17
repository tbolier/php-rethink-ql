<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Connection;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface ConnectionQueryInterface
{
    public function writeQuery(int $token, MessageInterface $message): int;

    public function continueQuery(int $token): ResponseInterface;

    public function stopQuery(int $token): ResponseInterface;
}
