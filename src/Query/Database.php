<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Operation\DbCreate;
use TBolier\RethinkQL\Query\Operation\DbDrop;
use TBolier\RethinkQL\Query\Operation\DbList;
use TBolier\RethinkQL\Query\Operation\TableCreate;
use TBolier\RethinkQL\Query\Operation\TableDrop;
use TBolier\RethinkQL\Query\Operation\TableList;
use TBolier\RethinkQL\RethinkInterface;

class Database extends AbstractQuery
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink

     */
    public function __construct(RethinkInterface $rethink)
    {
        parent::__construct($rethink);

        $this->dbList();
    }

    /**
     * @inheritdoc
     */
    public function dbCreate(string $name): Database
    {
        $this->query = new DbCreate($this->rethink, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dbDrop(string $name): Database
    {
        $this->query = new DbDrop($this->rethink, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dbList(): Database
    {
        $this->query = new DbList($this->rethink);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableList(): Database
    {
        $this->query = new TableList($this->rethink);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableCreate(string $name): Database
    {
        $this->query = new TableCreate($this->rethink, $name);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableDrop(string $name): Database
    {
        $this->query = new TableDrop($this->rethink, $name);

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
