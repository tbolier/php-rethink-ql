<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

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

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message = null)
    {
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        $this->message->setQuery($this->toArray());

        return $this->rethink->connection()->run($this->message);
    }

    /**
     * @return array
     */
    abstract public function toArray(): array;
}
