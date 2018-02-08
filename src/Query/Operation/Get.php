<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Get extends AbstractOperation
{
    /**
     * @var string
     */
    private $predicate;

    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $query
     * @param string $documents
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        QueryInterface $query,
        string $documents
    ) {
        parent::__construct($rethink, $message);

        $this->query = $query;
        $this->predicate = $documents;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            TermType::GET,
            [
                $this->query->toArray(),
                [
                    TermType::DATUM,
                    $this->predicate,
                ],
            ],
        ];
    }
}
