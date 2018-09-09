<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query\Manipulation;

use TBolier\RethinkQL\Query\QueryInterface;

trait ManipulationTrait
{
    public function pluck(...$keys): Pluck
    {
        return new Pluck($this->rethink, /** @scrutinizer ignore-type */ $this, $keys);
    }

    public function without(...$keys): Without
    {
        return new Without($this->rethink, /** @scrutinizer ignore-type */ $this, $keys);
    }

    public function keys(): Keys
    {
        return new Keys($this->rethink, /** @scrutinizer ignore-type */ $this);
    }

    public function values(): Values
    {
        return new Values($this->rethink, /** @scrutinizer ignore-type */ $this);
    }
}
