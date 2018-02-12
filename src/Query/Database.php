<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Operation\DbCreate;
use TBolier\RethinkQL\Query\Operation\DbDrop;
use TBolier\RethinkQL\Query\Operation\DbList;
use TBolier\RethinkQL\Query\Operation\TableCreate;
use TBolier\RethinkQL\Query\Operation\TableDrop;
use TBolier\RethinkQL\Query\Operation\TableList;
use TBolier\RethinkQL\RethinkInterface;

class Database extends AbstractQuery implements DatabaseInterface
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message)
    {
        parent::__construct($rethink, $message);

        $this->dbList();
    }

    /**
     * @inheritdoc
     */
    public function dbCreate(string $name): DatabaseInterface
    {
        $this->query = new DbCreate($this->rethink, $this->message, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dbDrop(string $name): DatabaseInterface
    {
        $this->query = new DbDrop($this->rethink, $this->message, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dbList(): DatabaseInterface
    {
        $this->query = new DbList($this->rethink, $this->message);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableList(): DatabaseInterface
    {
        $this->query = new TableList($this->rethink, $this->message);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableCreate(string $name): DatabaseInterface
    {
        $this->query = new TableCreate($this->rethink, $this->message, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableDrop(string $name): DatabaseInterface
    {
        $this->query = new TableDrop($this->rethink, $this->message, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query->toArray();
    }
}
