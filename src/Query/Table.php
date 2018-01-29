<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Term\TermType;

class Table implements TableInterface
{
    /**
     * @var RethinkInterface
     */
    private $rethink;

    /**
     * @var MessageInterface
     */
    private $message;

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
        $this->rethink = $rethink;
        $this->table = $name;

        $message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query([
                TermType::TABLE,
                [
                    $this->table,
                ],
            ]));

        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function count(): TableInterface
    {
        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query(
                [
                    TermType::COUNT,
                    [
                        [
                            TermType::TABLE,
                            [
                                $this->table,
                            ],
                        ],
                    ],
                ]
            ));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function filter(array $documents): TableInterface
    {
        $jsonDocuments = [];
        foreach ($documents as $key => $document) {
            $jsonDocuments[] = json_encode($document);
        }

        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query([
                TermType::FILTER,
                [
                    [
                        TermType::TABLE,
                        [
                            $this->table,
                        ],
                    ],
                    [
                        TermType::JSON,
                        $jsonDocuments,
                    ],
                ],
            ]));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function insert(array $documents): TableInterface
    {
        // TODO: Move encoding logic to QueryNormalizer.
        $jsonDocuments = [];
        foreach ($documents as $key => $document) {
            $jsonDocuments[] = json_encode($document);
        }

        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query(
                [
                    TermType::INSERT,
                    [
                        [
                            TermType::TABLE,
                            [
                                $this->table,
                            ],
                        ],
                        [
                            TermType::JSON,
                            $jsonDocuments,
                        ],
                    ],
                ]));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function update(array $documents): TableInterface
    {
        $jsonDocuments = [];
        foreach ($documents as $key => $document) {
            $jsonDocuments[] = json_encode($document);
        }

        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query(
                [
                    TermType::UPDATE,
                    [
                        [
                            TermType::TABLE,
                            [
                                $this->table,
                            ],
                        ],
                        [
                            TermType::JSON,
                            $jsonDocuments,
                        ],
                    ],
                ]));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function delete(): TableInterface
    {
        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query([
                TermType::DELETE,
                [
                    [
                        TermType::TABLE,
                        [
                            $this->table,
                        ],
                    ],
                ],
            ]));

        return $this;
    }

    /**
     * @param mixed $value
     * @return TableInterface
     */
    public function get($value): TableInterface
    {
        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query([
                TermType::GET,
                [
                    [
                        TermType::TABLE,
                        [
                            $this->table,
                        ],
                    ],
                    [
                        TermType::DATUM,
                        $value,
                    ],
                ],
            ]));

        return $this;
    }

    /**
     * @return mixed
     */
    public function run()
    {
        return $this->rethink->connection()->run($this->message);
    }
}
