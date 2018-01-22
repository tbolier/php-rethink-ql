<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Query\QueryType;
use TBolier\RethinkQL\Types\Term\TermType;

class Database implements DatabaseInterface
{
    /**T
     * @var RethinkInterface
     */
    private $rethink;

    /**
     * @var array
     */
    private $message;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message)
    {
        $this->rethink = $rethink;

        $message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query([]));

        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function tableList(): DatabaseInterface
    {
        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query(
                [
                    TermType::TABLE_LIST,
                ]
            ));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableCreate(string $name): DatabaseInterface
    {
        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query([
                TermType::TABLE_CREATE,
                [
                    [
                        TermType::DATUM,
                        $name,
                    ],
                ],
            ]));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableDrop(string $name): DatabaseInterface
    {
        $this->message
            ->setQueryType(QueryType::START)
            ->setQuery(new Query([
                TermType::TABLE_DROP,
                [
                    [
                        TermType::DATUM,
                        $name,
                    ],
                ],
            ]));

        return $this;
    }

    /**
     * @return array
     */
    public function run(): array
    {
        return $this->rethink->connection()->run($this->message);
    }
}
