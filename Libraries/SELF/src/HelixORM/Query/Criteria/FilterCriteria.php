<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * Class OrderCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
class FilterCriteria extends ColumnQueryCriteria
{
    private static int $countBinds = 1;

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
        if ($this->getComperision()->isLike()) {
            return '%' . $this->value . '%';
        }

        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        $sql = $this->getColumn() . ' ' . $this->getComperision()->value . ' ';
        $criteria = $this->getComperision();

        if ($criteria->isIn()) {
            $sql .= "(";
            if (is_array($this->getValue())) {
                $sql .= str_repeat("?,", count($this->getValue()));
                $sql = rtrim($sql, ',');
            } else {
                $sql .= "?";
            }

            $sql .= ")";
        } else {
            $sql .= "?";
        }

        return $sql;
    }

    public function bindValue(\PDOStatement &$statement, TableColumn $column): void
    {
        $value = $this->getValue();
        if ($this->getComperision()->isIn()) {
            if (is_array($value)) {
                foreach ($value as $seperateValue) {
                    $statement->bindValue(self::$countBinds++, $seperateValue, $column->getType()->getPdoType());
                }

                return;
            }
        }

        $statement->bindValue(self::$countBinds++, $value, $column->getType()->getPdoType());
    }
}