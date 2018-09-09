<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Query\Exception\QueryException;
use TBolier\RethinkQL\Query\Logic\AndLogic;
use TBolier\RethinkQL\Query\Logic\EqualLogic;
use TBolier\RethinkQL\Query\Logic\FuncLogic;
use TBolier\RethinkQL\Query\Logic\GreaterThanLogic;
use TBolier\RethinkQL\Query\Logic\GreaterThanOrEqualToLogic;
use TBolier\RethinkQL\Query\Logic\LowerThanLogic;
use TBolier\RethinkQL\Query\Logic\LowerThanOrEqualToLogic;
use TBolier\RethinkQL\Query\Logic\NotEqualLogic;
use TBolier\RethinkQL\Query\Logic\NotLogic;
use TBolier\RethinkQL\Query\Logic\OrLogic;
use TBolier\RethinkQL\Query\Manipulation\GetField;
use TBolier\RethinkQL\Query\Manipulation\RowHasFields;
use TBolier\RethinkQL\Query\Manipulation\ManipulationTrait;
use TBolier\RethinkQL\RethinkInterface;

class Row extends AbstractQuery
{
    use ManipulationTrait;

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

    public function __construct(
        RethinkInterface $rethink,
        ?string $value
    ) {
        parent::__construct($rethink);

        $this->value = $value;
        $this->rethink = $rethink;
    }

    /**
     * @throws QueryException
     */
    public function eq($value): Row
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->function = new EqualLogic(
            $this->rethink,
            new GetField($this->rethink, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    /**
     * @throws QueryException
     */
    public function ne($value): Row
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->function = new NotEqualLogic(
            $this->rethink,
            new GetField($this->rethink, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    /**
     * @throws QueryException
     */
    public function lt($value): Row
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than manipulations.');
        }

        $this->function = new LowerThanLogic(
            $this->rethink,
            new GetField($this->rethink, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    /**
     * @throws QueryException
     */
    public function le($value): Row
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for lower than or equal manipulations.');
        }

        $this->function = new LowerThanOrEqualToLogic(
            $this->rethink,
            new GetField($this->rethink, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    /**
     * @throws QueryException
     */
    public function gt($value): Row
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for greater than manipulations.');
        }

        $this->function = new GreaterThanLogic(
            $this->rethink,
            new GetField($this->rethink, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    /**
     * @throws QueryException
     */
    public function ge($value): Row
    {
        if (!\is_scalar($value)) {
            throw new QueryException('Only scalar types are supported for greater than or equal manipulations.');
        }

        $this->function = new GreaterThanOrEqualToLogic(
            $this->rethink,
            new GetField($this->rethink, $this->value),
            $value
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    public function and(Row $row): Row
    {
        $this->function = new AndLogic(
            $this->rethink,
            $this->function,
            $row->getFunction()
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    public function or(Row $row): Row
    {
        $this->function = new OrLogic(
            $this->rethink,
            $this->function,
            $row->getFunction()
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    public function not(): Row
    {
        $this->function = new NotLogic(
            $this->rethink,
            $this->function
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    public function hasFields(...$keys)
    {
        $this->function = new RowHasFields(
            $this->rethink,
            $keys
        );

        $this->query = new FuncLogic(
            $this->rethink,
            $this->function
        );

        return $this;
    }

    public function toArray(): array
    {
        return $this->query->toArray();
    }

    public function getFunction(): QueryInterface
    {
        return $this->function;
    }
}
