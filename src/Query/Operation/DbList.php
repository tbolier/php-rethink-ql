<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Operation;

use TBolier\RethinkQL\Query\MessageInterface;
use TBolier\RethinkQL\RethinkInterface;
use TBolier\RethinkQL\Types\Term\TermType;

class DbList extends AbstractOperation
{
    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     */
    public function __construct(RethinkInterface $rethink, MessageInterface $message)
    {
        parent::__construct($rethink, $message);

        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            TermType::DB_LIST,
        ];
    }
}
