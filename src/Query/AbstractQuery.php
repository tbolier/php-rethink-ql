<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\Message;
use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\RethinkInterface;

abstract class AbstractQuery implements QueryInterface
{
    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * @var RethinkInterface
     */
    protected $rethink;

    public function __construct(RethinkInterface $rethink)
    {
        $this->rethink = $rethink;
    }

    public function run()
    {
        $message = new Message();
        $message->setQuery($this->toArray());

        return $this->rethink->connection()->run($message);
    }

    abstract public function toArray(): array;
}
