<?php

namespace SELF\src\Helpers\Interfaces\Database\HelixORM\Query;

use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

interface BindableColumnInterface extends CriteriaInterface
{
    public function bindValue(\PDOStatement &$statement): void;
}