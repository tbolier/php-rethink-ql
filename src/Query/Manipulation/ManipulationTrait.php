<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Manipulation;

use TBolier\RethinkQL\Query\QueryInterface;

trait ManipulationTrait
{
    public function pluck(...$keys): QueryInterface
    {
        return new Pluck($this->rethink, /** @scrutinizer ignore-type */ $this, $keys);
    }

    public function without(...$keys)
    {
        return new Without($this->rethink, /** @scrutinizer ignore-type */ $this, $keys);
    }

    public function hasFields(...$keys)
    {
        return new HasFields($this->rethink, /** @scrutinizer ignore-type */ $this, $keys);
    }

    public function keys(): QueryInterface
    {
        return new Keys($this->rethink, /** @scrutinizer ignore-type */ $this);
    }

    public function values(): QueryInterface
    {
        return new Values($this->rethink, /** @scrutinizer ignore-type */ $this);
    }
}
