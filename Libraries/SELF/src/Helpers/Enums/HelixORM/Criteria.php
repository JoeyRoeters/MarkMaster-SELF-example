<?php

namespace SELF\src\Helpers\Enums\HelixORM;

use SELF\src\Exceptions\HelixORM\CriteriaException;
use SELF\src\HelixORM\Query\Criteria\FilterCriteria;
use SELF\src\HelixORM\Query\Criteria\LimitCriteria;
use SELF\src\HelixORM\Query\Criteria\OrderCriteria;
use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\CriteriaInterface;

enum Criteria: string
{
    case EQUALS = '=';
    case NOT_EQUALS = '!=';
    case GREATER_THAN = '>';
    case GREATER_THAN_OR_EQUALS = '>=';
    case LESS_THAN = '<';
    case LESS_THAN_OR_EQUALS = '<=';
    case LIKE = 'LIKE';
    case NOT_LIKE = 'NOT LIKE';
    case IN = 'IN';
    case NOT_IN = 'NOT IN';
    case BETWEEN = 'BETWEEN';
    case NOT_BETWEEN = 'NOT BETWEEN';
    case IS_NULL = 'IS NULL';
    case IS_NOT_NULL = 'IS NOT NULL';
    case DESC = 'DESC';
    case ASC = 'ASC';

    public function isNull(): bool
    {
        return $this === self::IS_NULL || $this === self::IS_NOT_NULL;
    }

    public function isBetween(): bool
    {
        return $this === self::BETWEEN || $this === self::NOT_BETWEEN;
    }

    public function isIn(): bool
    {
        return $this === self::IN || $this === self::NOT_IN;
    }

    public function isLike(): bool
    {
        return $this === self::LIKE || $this === self::NOT_LIKE;
    }

    public function isOrder(): bool
    {
        return $this === self::DESC || $this === self::ASC;
    }

    public function isFilter(): bool
    {
        return !$this->isOrder();
    }

    private function criteriaWeight(CriteriaInterface $criteria): int
    {
        return match (true) {
            $criteria instanceof FilterCriteria => 1,
            $criteria instanceof OrderCriteria => 2,
            $criteria instanceof LimitCriteria => 3,
            default => throw new CriteriaException('Criteria weight not set. Criteria: ' . get_class($criteria))
        };
    }

    public function sortCriterias(array $criterias): array
    {

    }
}