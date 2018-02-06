<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Operation\AbstractOperation;
use TBolier\RethinkQL\Query\Operation\Filter;
use TBolier\RethinkQL\Query\Operation\Get;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Table extends AbstractOperation
{
    /**
     * @var array
     */
    private $query;

    /**
     * @var string
     */
    private $table;

    /**
     * @param string $name
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(string $name, RethinkInterface $rethink, MessageInterface $message)
    {
        parent::__construct($rethink, $message);

        $this->table = $name;
        $this->rethink = $rethink;
        $this->message = $message;

        $this->query = [
            TermType::TABLE,
            [
                $this->table,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function filter(array $documents): AbstractQuery
    {
        $filter = new Filter($this->rethink, $this->message, $this, $documents);

        return $filter;
    }

    /**
     * @inheritdoc
     */
    public function get($value): AbstractQuery
    {
        $get = new Get($this->rethink, $this->message, $this, $value);

        return $get;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query;
    }
}
