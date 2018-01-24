<?php

namespace TBolier\RethinkQL\Query;


use TBolier\RethinkQL\Connection\ConnectionInterface;

class Cursor implements \Iterator
{
    /**
     * @var ConnectionInterface
     */
    private $connection;

    /**
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        // TODO: Implement current() method.
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        // TODO: Implement next() method.
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        // TODO: Implement key() method.
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        // TODO: Implement valid() method.
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        // TODO: Implement rewind() method.
    }
}
