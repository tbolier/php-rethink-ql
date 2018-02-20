<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query\Transformation;

use TBolier\RethinkQL\Query\QueryInterface;
use TBolier\RethinkQL\Response\ResponseInterface;

interface TransformationInterface
{
    /**
     * @return QueryInterface
     */
    public function isEmpty(): QueryInterface;

    /**
     * @param int $n
     * @return TransformationInterface
     */
    public function limit($n): TransformationInterface;

    /**
     * @param int $n
     * @return TransformationInterface
     */
    public function skip($n): TransformationInterface;

    /**
     * @param mixed|QueryInterface $key
     * @return TransformationInterface
     */
    public function orderBy($key): TransformationInterface;

    /**
     * @return Iterable|ResponseInterface
     */
    public function run();
}
