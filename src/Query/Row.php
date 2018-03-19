<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Message\MessageInterface;
use TBolier\RethinkQL\Query\Exception\QueryException;
use TBolier\RethinkQL\Query\Logic\AndLogic;
use TBolier\RethinkQL\Query\Logic\EqualLogic;
use TBolier\RethinkQL\Query\Logic\FuncLogic;
use TBolier\RethinkQL\Query\Logic\GetFieldLogic;
use TBolier\RethinkQL\Query\Logic\GreaterThanLogic;
use TBolier\RethinkQL\Query\Logic\LowerThanLogic;
use TBolier\RethinkQL\Query\Logic\NotEqualLogic;
use TBolier\RethinkQL\Query\Logic\OrLogic;
use TBolier\RethinkQL\RethinkInterface;

class Row extends AbstractQuery implements RowInterface
{
    /**
     * @var QueryInterface
     */
    private $function;

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
        $this->value = $value;
        $this->rethink = $rethink;
        $this->message = $message;
    }

    /**
     * @inheritdoc
     * @throws QueryException
     */
    public function eq($value): RowInterface
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->function = new EqualLogic(
            $this->rethink,
            $this->message,
            new GetFieldLogic($this->rethink, $this->message, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            $this->function
        );

        return $this;
    }

    /**
     * @inheritdoc
     * @throws QueryException
     */
    public function ne($value): RowInterface
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->function = new NotEqualLogic(
            $this->rethink,
            $this->message,
            new GetFieldLogic($this->rethink, $this->message, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            $this->function
        );

        return $this;
    }

    /**
     * @inheritdoc
     * @throws QueryException
     */
    public function lt($value): RowInterface
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->function = new LowerThanLogic(
            $this->rethink,
            $this->message,
            new GetFieldLogic($this->rethink, $this->message, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            $this->function
        );

        return $this;
    }

    /**
     * @inheritdoc
     * @throws QueryException
     */
    public function gt($value): RowInterface
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for greater than manipulations.');
        }

        $this->function = new GreaterThanLogic(
            $this->rethink,
            $this->message,
            new GetFieldLogic($this->rethink, $this->message, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            $this->function
        );

        return $this;
    }

    /**
     * @param RowInterface $row
     * @return RowInterface
     */
    public function and(RowInterface $row): RowInterface
    {
        $this->function = new AndLogic(
            $this->rethink,
            $this->message,
            $this->function,
            $row->getFunction()
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            $this->function
        );

        return $this;
    }

    /**
     * @param RowInterface $row
     * @return RowInterface
     */
    public function or(RowInterface $row): RowInterface
    {
        $this->function = new OrLogic(
            $this->rethink,
            $this->message,
            $this->function,
            $row->getFunction()
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->message,
            $this->function
        );

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return $this->query->toArray();
    }

    /**
     * @return QueryInterface
     */
    public function getFunction(): QueryInterface
    {
        return $this->function;
    }
}
