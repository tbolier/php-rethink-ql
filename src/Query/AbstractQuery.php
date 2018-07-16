<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\Message;
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
     */
    public function __construct(RethinkInterface $rethink)
    {
        $this->rethink = $rethink;
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $message = new Message();
        $message->setQuery($this->toArray());

        return $this->rethink->connection()->run($message);
    }

    /**
     * @return array
     */
    abstract public function toArray(): array;
}
