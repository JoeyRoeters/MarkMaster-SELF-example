<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\Criteria;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\ColumnCriteriaInterface;

/**
 * Class ColumnQueryCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
abstract class ColumnQueryCriteria extends QueryCriteria implements ColumnCriteriaInterface
{
    public function __construct(
        private TableColumn $tableColumn,
        private Criteria $comperision = Criteria::EQUALS,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getComperision(): Criteria
    {
        return $this->comperision;
    }

    /**
     * @inheritDoc
     */
    public function getTableColumn(): TableColumn
    {
        return $this->tableColumn;
    }

    /**
     * @inheritDoc
     */
    public function criteriaIdentifier(): string
    {
        return static::class . $this->getTableColumn()->getName();
    }
}