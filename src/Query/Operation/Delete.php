<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\MessageInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Delete extends AbstractOperation
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $query
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message, QueryInterface $query)
    {
        parent::__construct($rethink, $message);

        $this->query = $query;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            TermType::DELETE,
            [
                $this->query->toArray(),
            ],
        ];
    }
}
