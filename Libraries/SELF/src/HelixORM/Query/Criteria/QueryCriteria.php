<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\CriteriaInterface;

/**
 * Class QueryCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
abstract class QueryCriteria implements CriteriaInterface
{
    /**
     * @inheritDoc
     */
    public function criteriaIdentifier(): string
    {
        return static::class;
    }
}