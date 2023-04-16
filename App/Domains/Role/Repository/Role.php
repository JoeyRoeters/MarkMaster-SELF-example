<?php

namespace App\Domains\Role\Repository;

use App\Enums\RoleEnum;
use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

class Role extends ActiveRecord
{
    protected string $table = 'roles';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('name'),
        ];
    }

    public static function findOrCreate(RoleEnum $roleEnum): self
    {
        $role = RoleQuery::create()
            ->filterByName($roleEnum->value)
            ->findOne();

        if ($role === null) {
            $role = new Role();
            $role->set('name', $roleEnum->value);
            $role->save();
        }

        return $role;
    }
}