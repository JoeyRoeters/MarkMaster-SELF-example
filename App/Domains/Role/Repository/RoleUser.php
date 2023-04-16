<?php

namespace App\Domains\Role\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

class RoleUser extends ActiveRecord
{
    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id')->setPrimaryKey(true),
            TableColumn::create('role_id', ColumnType::INT),
            TableColumn::create('user_id', ColumnType::INT),
        ];
    }
}