<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Aggregation\Limit;
use TBolier\RethinkQL\Query\Aggregation\OrderBy;
use TBolier\RethinkQL\Query\Aggregation\AggregationInterface;
use TBolier\RethinkQL\Query\Operation\AbstractOperation;
use TBolier\RethinkQL\Query\Operation\Get;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Table extends AbstractOperation implements TableInterface
{
    /**
     * @var array
     */
    private $query;

    /**
     * @param string $name
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(string $name, RethinkInterface $rethink, MessageInterface $message)
    {
        parent::__construct($rethink, $message);

        $this->rethink = $rethink;
        $this->message = $message;

        $this->query = [
            TermType::TABLE,
            [
                $name,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function get($value): QueryInterface
    {
        return new Get($this->rethink, $this->message, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function limit($value): AggregationInterface
    {
        return new Limit($this->rethink, $this->message, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function orderBy($key): AggregationInterface
    {
        return new OrderBy($this->rethink, $this->message, $this, $key);
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query;
    }
}
