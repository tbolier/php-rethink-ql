<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Count extends AbstractOperation
{
    /**
     * @var AbstractQuery
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param AbstractQuery $query
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message, AbstractQuery $query)
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
            TermType::COUNT,
            [
                $this->query->toArray(),
            ],
        ];
    }
}
