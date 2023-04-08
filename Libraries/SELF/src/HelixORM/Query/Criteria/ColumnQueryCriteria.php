<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\Helpers\Enums\HelixORM\Criteria;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\ColumnCriteriaInterface;

/**
 * Class ColumnQueryCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
abstract class ColumnQueryCriteria extends QueryCriteria implements ColumnCriteriaInterface
{
    public function __construct(
        private string $column,
        private Criteria $criteria = Criteria::EQUALS,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function getCriteria(): Criteria
    {
        return $this->criteria;
    }

    /**
     * @inheritDoc
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @inheritDoc
     */
    public function criteriaIdentifier(): string
    {
        return static::class . $this->getColumn();
    }
}