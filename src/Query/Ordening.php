<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class Ordening extends AbstractQuery implements OrdeningInterface
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @param string $key
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(string $key, RethinkInterface $rethink, MessageInterface $message)
    {
        parent::__construct($rethink, $message);

        $this->asc($key);
    }

    /**
     * @inheritdoc
     */
    public function asc(string $key): OrdeningInterface
    {
        $this->query = [
            TermType::ASC,
            [
                $key,
            ],
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function desc(string $key): OrdeningInterface
    {
        $this->query = [
            TermType::DESC,
            [
                $key,
            ],
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query;
    }
}
