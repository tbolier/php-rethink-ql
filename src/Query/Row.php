<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Exception\QueryException;
use TBolier\RethinkQL\Query\Logic\EqualLogic;
use TBolier\RethinkQL\Query\Logic\FuncLogic;
use TBolier\RethinkQL\Query\Logic\GetFieldLogic;
use TBolier\RethinkQL\Query\Logic\GreaterThanLogic;
use TBolier\RethinkQL\Query\Logic\LowerThanLogic;
use TBolier\RethinkQL\Query\Logic\NotEqualLogic;
use TBolier\RethinkQL\Query\Manipulation\ManipulationInterface;
use TBolier\RethinkQL\RethinkInterface;

class Row extends AbstractQuery implements ManipulationInterface
{
    /**
     * @var QueryInterface
     */
    private $query;

    /**
     * @var string
     */
    private $value;

    /**
     * @param RethinkInterface $rethink
     * @param MessageInterface $message
     * @param string $value
     */
    public function __construct(
        RethinkInterface $rethink,
        MessageInterface $message,
        string $value
    ) {
        parent::__construct($rethink, $message);

        $this->value = $value;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     * @throws QueryException
     */
    public function eq($value): QueryInterface
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            new EqualLogic(
                $this->rethink,
                $this->message,
                new GetFieldLogic($this->rethink, $this->message, $this->value),
                $value
            )
        );

        return $this->query;
    }

    /**
     * @inheritdoc
     * @throws QueryException
     */
    public function ne($value): QueryInterface
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            new NotEqualLogic(
                $this->rethink,
                $this->message,
                new GetFieldLogic($this->rethink, $this->message, $this->value),
                $value
            )
        );

        return $this->query;
    }

    /**
     * @inheritdoc
     * @throws QueryException
     */
    public function lt($value): QueryInterface
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            new LowerThanLogic(
                $this->rethink,
                $this->message,
                new GetFieldLogic($this->rethink, $this->message, $this->value),
                $value
            )
        );

        return $this->query;
    }

    /**
     * @inheritdoc
     * @throws QueryException
     */
    public function gt($value): QueryInterface
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for greater than manipulations.');
        }

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            new GreaterThanLogic(
                $this->rethink,
                $this->message,
                new GetFieldLogic($this->rethink, $this->message, $this->value),
                $value
            )
        );

        return $this->query;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query->toArray();
    }
}
