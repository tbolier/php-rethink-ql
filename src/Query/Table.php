<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Connection\ConnectionInterface;
use TBolier\RethinkQL\Connection\Exception;
use TBolier\RethinkQL\Document\ManagerInterface;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Term\TermType;

class Table implements TableInterface
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @var array
     */
    private $query;

    /**
     * @var string
     */
    private $name;

    /**
     * @param ManagerInterface $manager
     * @param string $name
     */
    public function __construct(ManagerInterface $manager, string $name)
    {
        $this->manager = $manager;
        $this->manager->getConnection()->selectTable($name);
        $this->name = $name;

        $this->query = [
            QueryType::START,
            [TermType::TABLE, [[TermType::DB, ['booking']], $this->name]],
            (object)[]
        ];

    }

    /**
     * @inheritdoc
     */
    public function count(): TableInterface
    {
        // Todo: Build count query
        $this->query = [
            QueryType::START,
            [], // Write query here
            (object)[]
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function insert(array $documents): TableInterface
    {
        $jsonDocuments = [];
        foreach ($documents as $key => $document) {
            $jsonDocuments[] = json_encode($documents);
        }

        $this->query = [
            QueryType::START,
            [
                TermType::INSERT,
                [
                    [
                        TermType::TABLE,
                        [
                            [
                                TermType::DB,
                                ['booking'],
                                (object)[]
                            ],
                            $this->name,
                        ],
                        (object)[],
                    ],
                    [
                        TermType::JSON,
                        $jsonDocuments,
                        (object)[],
                    ],
                ],
                (object)[]
            ],
            (object)[
                'db' => [
                    TermType::DB,
                    ['booking'],
                    (object)[]
                ],
            ]
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function update(array $documents): TableInterface
    {
        // Todo: Build upsert query
        $this->query = [
            QueryType::START,
            [], // Write query here
            (object)[]
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function remove(array $documents): TableInterface
    {
        // Todo: Build remove query
        $this->query = [
            QueryType::START,
            [], // Write query here
            (object)[]
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function execute(): array
    {
        return $this->manager->getConnection()->execute($this->query);
    }
}
