<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\AbstractQuery;
use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Insert extends AbstractQuery
{
    /**
     * @var array
     */
    private $documents;
    /**
     * @var QueryInterface
     */
    private $query;

    public function __construct(
        RethinkInterface $rethink,
        QueryInterface $query,
        array $documents
    ) {
        parent::__construct($rethink);

        $this->query = $query;
        $this->documents = [$documents];
        $this->rethink = $rethink;
    }

    public function toArray(): array
    {
        $jsonDocuments = [];
        foreach ($this->documents as $key => $document) {
            $jsonDocuments[] = json_encode($document);
        }

        return [
            TermType::INSERT,
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
