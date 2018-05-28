<?php

namespace TBolier\RethinkQL\Query;

interface RowInterface extends QueryInterface
{
    /**
     * @param mixed $value
     * @return RowInterface
     */
    public function eq($value): RowInterface;

    /**
     * @param mixed $value
     * @return RowInterface
     */
    public function ne($value): RowInterface;

    /**
     * @param mixed $value
     * @return RowInterface
     */
    public function lt($value): RowInterface;

    /**
     * @param mixed $value
     * @return RowInterface
     */
    public function le($value): RowInterface;

    /**
     * @param mixed $value
     * @return RowInterface
     */
    public function gt($value): RowInterface;

    /**
     * @param mixed $value
     * @return RowInterface
     */
    public function ge($value): RowInterface;

    /**
     * @param RowInterface $value
     * @return RowInterface
     */
    public function and(RowInterface $value): RowInterface;

    /**
     * @param RowInterface $value
     * @return RowInterface
     */
    public function or(RowInterface $value): RowInterface;

    /**
     * @return QueryInterface
     */
    public function getFunction(): QueryInterface;
}
