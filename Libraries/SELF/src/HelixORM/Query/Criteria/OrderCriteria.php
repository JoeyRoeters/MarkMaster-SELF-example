<?php

namespace SELF\src\HelixORM\Query\Criteria;

use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * Class OrderCriteria
 * @package SELF\src\HelixORM\Query\Criteria
 */
class OrderCriteria extends ColumnQueryCriteria
{
    public function __construct(
        TableColumn $column,
        Criteria $sort = Criteria::ASC,
    ) {
        parent::__construct($column, $sort);
    }

    /**
     * @inheritDoc
     */
    public function getSql(): string
    {
        return $this->getTableColumn()->getName() . ' ' . $this->getComperision()->value;
    }
}