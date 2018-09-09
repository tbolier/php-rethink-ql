<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\RethinkInterface;

class Builder
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var Ordening
     */
    private $ordering;

    /**
     * @var RethinkInterface
     */
    private $rethink;

    /**
     * @var Row
     */
    private $row;

    /**
     * @var Table
     */
    private $table;

    public function __construct(RethinkInterface $rethink)
    {
        $this->rethink = $rethink;
    }

    public function table(string $name): Table
    {
        if ($this->table) {
            unset($this->table);
        }

        $this->table = new Table($name, $this->rethink);

        return $this->table;
    }

    public function database(): Database
    {
        if ($this->database) {
            unset($this->database);
        }

        $this->database = new Database($this->rethink);

        return $this->database;
    }

    public function ordening(string $key): Ordening
    {
        if ($this->ordering) {
            unset($this->ordering);
        }

        $this->ordering = new Ordening($key, $this->rethink);

        return $this->ordering;
    }

    public function row(string $value = null): Row
    {
        $this->row = new Row($this->rethink, $value);

        return $this->row;
    }
}
