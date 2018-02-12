<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Update extends AbstractQuery
{
    /**
     * @var array
     */
    private $elements;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $query
     * @param array $elements
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        QueryInterface $query,
        array $elements
    ) {
        parent::__construct($rethink, $message);

        $this->query = $query;
        $this->elements = $elements;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $jsonElements = json_encode($this->elements);

        return [
            TermType::UPDATE,
            [
                $this->query->toArray(),
                [
                    TermType::JSON,
                    [
                        $jsonElements
                    ],
                ],
            ],
        ];
    }
}
