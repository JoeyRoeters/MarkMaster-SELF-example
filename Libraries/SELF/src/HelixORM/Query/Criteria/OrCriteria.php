<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\Helpers\Interfaces\Database\HelixORM\Query\OperatorCriteriaInterface;

/**
 * Class EndOrCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
class OrCriteria extends QueryCriteria implements OperatorCriteriaInterface
{
    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return ' OR ';
    }
}