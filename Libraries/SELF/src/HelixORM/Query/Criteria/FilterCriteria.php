<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * Class OrderCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
class FilterCriteria extends ColumnQueryCriteria
{
    public function __construct(
        string $column,
        Criteria $comperison,
        private mixed $value
    )
    {
        parent::__construct($column, $comperison);
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * get the parameter name for the column for binding
     *
     * @return string
     */
    public function getColumnParam(): string
    {
        return $this->getColumn() . '_param';
    }

    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        $sql = $this->getColumn() . ' ' . $this->getCriteria()->value . ' ';
        $criteria = $this->getCriteria();
        if ($criteria->isLike()) {
            $sql .= "%:{$this->getColumnParam()}%";
        } elseif ($criteria->isIn()) {
            $sql .= "(:{$this->getColumnParam()})";
        } else {
            $sql .= ":{$this->getColumnParam()}";
        }

        return $sql;
    }
}