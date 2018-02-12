<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Filter extends AbstractOperation
{
    /**
     * @var array
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
     * @param array $predicate
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        QueryInterface $query,
        array $predicate
    ) {
        parent::__construct($rethink, $message);

        $this->query = $query;
        $this->predicate = [$predicate];
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $jsonDocuments = [];
        foreach ($this->predicate as $key => $document) {
            $jsonDocuments[] = json_encode($document);
        }

        return [
            TermType::FILTER,
            [
                $this->query->toArray(),
                [
                    TermType::JSON,
                    $jsonDocuments,
                ],
            ],
        ];
    }
}
