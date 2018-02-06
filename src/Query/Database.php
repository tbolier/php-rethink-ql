<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Response\ResponseInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Database extends AbstractQuery implements DatabaseInterface
{
    /**
     * @var array
     */
    private $query;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message)
    {
        parent::__construct($rethink, $message);

        $this->query = [
            TermType::DB_LIST,
        ];
    }

    /**
     * @inheritdoc
     */
    public function dbCreate(string $name): DatabaseInterface
    {
        $this->query = [
            TermType::DB_CREATE,
            [
                [
                    TermType::DATUM,
                    $name,
                ],
            ],
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dbDrop(string $name): DatabaseInterface
    {
        $this->query = [
            TermType::DB_DROP,
            [
                [
                    TermType::DATUM,
                    $name,
                ],
            ],
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function dbList(): DatabaseInterface
    {
        $this->query = [
            TermType::DB_LIST,
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableList(): DatabaseInterface
    {
        $this->query = [
            TermType::TABLE_LIST,
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableCreate(string $name): DatabaseInterface
    {
        $this->query = [
            TermType::TABLE_CREATE,
            [
                [
                    TermType::DATUM,
                    $name,
                ],
            ],
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function tableDrop(string $name): DatabaseInterface
    {
        $this->query = [
            TermType::TABLE_DROP,
            [
                [
                    TermType::DATUM,
                    $name,
                ],
            ],
        ];

        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function run(): ResponseInterface
    {
        $this->message->setQuery($this->toArray());

        return $this->rethink->connection()->run($this->message);
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query;
    }
}
