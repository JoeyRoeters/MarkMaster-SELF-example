<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\HelixORM\Query\QueryBuilder;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\BindableColumnInterface;

/**
 * Class OrderCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
class UpdateCriteria extends ColumnQueryCriteria implements BindableColumnInterface
{
    public function __construct(
        TableColumn $column,
        private mixed $value
    )
    {
        parent::__construct($column);
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        if ($this->getTableColumn()->getType() === ColumnType::DATETIME) {
            if ($this->value instanceof \DateTime) {
                return $this->value->format('Y-m-d H:i:s');
            }
        }

        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->getTableColumn()->getName() . ' = ?';
    }

    public function bindValue(\PDOStatement &$statement): void
    {
        $value = $this->getValue();
        $statement->bindValue(QueryBuilder::$countBinds++, $value, $this->getTableColumn()->getType()->getPdoType());
    }
}