<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\RethinkInterface;

class Builder implements BuilderInterface
{
    /**
     * @var RethinkInterface
     */
    private $rethink;

    /**
     * @var Table
     */
    private $table;

    /**
     * @var DatabaseInterface
     */
    private $database;

    /**
     * @var OrdeningInterface
     */
    private $ordering;

    /**
     * @var MessageInterface
     */
    private $message;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message)
    {
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @param string $name
     * @return TableInterface
     */
    public function table(string $name): TableInterface
    {
        if ($this->table) {
            unset($this->table);
        }

        $this->table = new Table($name, $this->rethink, $this->message);

        return $this->table;
    }

    /**
     * @return DatabaseInterface
     */
    public function database(): DatabaseInterface
    {
        if ($this->database) {
            unset($this->database);
        }

        $this->database = new Database($this->rethink, $this->message);

        return $this->database;
    }

    /**
     * @param string $key
     * @return OrdeningInterface
     */
    public function ordening(string $key): OrdeningInterface
    {
        if ($this->ordering) {
            unset($this->ordering);
        }

        $this->ordering = new Ordening($key, $this->rethink, $this->message);

        return $this->ordering;
    }
}
