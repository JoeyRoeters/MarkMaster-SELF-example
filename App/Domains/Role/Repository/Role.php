<?php

namespace App\Domains\Role\Repository;

use App\Enums\RoleEnum;
use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

class Role extends ActiveRecord
{
    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('name'),
        ];
    }

    public static function findOrCreate(RoleEnum $roleEnum): self
    {

    }
}