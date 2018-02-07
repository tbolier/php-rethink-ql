<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\Query;

use TBolier\RethinkQL\Types\Term\TermType;

class Options implements OptionsInterface
{
    /**
     * @var array
     */
    private $db;

    /**
     * @param string $name
     * @return OptionsInterface
     */
    public function setDb(string $name): OptionsInterface
    {
        $this->db = [
            TermType::DB,
            [$name],
        ];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        if (empty($this->db)) {
            return [];
        }

        return [
            'db' => $this->db
        ];
    }
}
