<?php
declare(strict_types = 1);

namespace TBolier\RethinkQL\Query;

interface OptionsInterface extends \JsonSerializable
{
    public function setDb(string $name): OptionsInterface;
}
