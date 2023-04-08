<?php

namespace SELF\src\HelixORM\Query\Criteria;

/**
 * Class LimitCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
class LimitCriteria extends QueryCriteria
{
    public function __construct(
        private int $limit,
    ) {}

    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return 'LIMIT ' . $this->limit;
    }
}