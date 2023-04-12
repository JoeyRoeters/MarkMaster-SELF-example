<?php

namespace SELF\src\Helpers\Interfaces\Database\HelixORM\Query;

use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

interface ColumnCriteriaInterface extends CriteriaInterface
{
    /**
     * @return Criteria
     */
    public function getComperision(): Criteria;

    /**
     * @return string
     */
    public function getTableColumn(): TableColumn;
}