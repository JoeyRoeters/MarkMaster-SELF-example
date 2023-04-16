<?php

namespace App\Domains\Role\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

/**
 * @method self setRoleId(int $roleId)
 * @method self setUserId(int $userId)
 */
class RoleUser extends ActiveRecord
{
    protected string $table = 'role_user';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id')->setPrimaryKey(true),
            TableColumn::create('role_id', ColumnType::INT),
            TableColumn::create('user_id', ColumnType::INT),
        ];
    }
}