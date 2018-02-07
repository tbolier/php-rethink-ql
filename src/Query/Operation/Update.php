<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Update extends AbstractOperation
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var array
     */
    private $documents;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param QueryInterface $query
     * @param array $documents
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message, QueryInterface $query, array $documents)
    {
        parent::__construct($rethink, $message);

        $this->query = $query;
        $this->documents = $documents;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        $jsonDocuments = [];
        foreach ($this->documents as $key => $document) {
            $jsonDocuments[] = json_encode($document);
        }

        return [
            TermType::UPDATE,
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
