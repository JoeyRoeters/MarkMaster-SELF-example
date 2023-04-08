<?php

namespace SELF\src\Helpers\Interfaces\Database\HelixORM\Query;

use SELF\src\Helpers\Enums\HelixORM\Criteria;

interface ColumnCriteriaInterface extends CriteriaInterface
{
    /**
     * @return Criteria
     */
    public function getCriteria(): Criteria;

    /**
     * @return string
     */
    public function getColumn(): string;
}