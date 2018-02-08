<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Operation\AbstractOperation;
use TBolier\RethinkQL\Query\Operation\Filter;
use TBolier\RethinkQL\Query\Operation\Get;
use TBolier\RethinkQL\Query\Operation\OperationInterface;
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
    public function get(string $value): OperationInterface
    {
        return new Get($this->rethink, $this->message, $this, $value);
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query;
    }
}
