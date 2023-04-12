<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\HelixORM\Query\QueryBuilder;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\Criteria;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\BindableColumnInterface;

/**
 * Class OrderCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
class FilterCriteria extends ColumnQueryCriteria implements BindableColumnInterface
{
    public function __construct(
        TableColumn $column,
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
        $sql = $this->getTableColumn()->getName() . ' ' . $this->getComperision()->value . ' ';
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

    public function bindValue(\PDOStatement &$statement): void
    {
        $value = $this->getValue();
        if ($this->getComperision()->isIn()) {
            if (is_array($value)) {
                foreach ($value as $seperateValue) {
                    $statement->bindValue(QueryBuilder::$countBinds++, $seperateValue, $this->getTableColumn()->getType()->getPdoType());
                }

                return;
            }
        }

        $statement->bindValue(QueryBuilder::$countBinds++, $value, $this->getTableColumn()->getType()->getPdoType());
    }
}