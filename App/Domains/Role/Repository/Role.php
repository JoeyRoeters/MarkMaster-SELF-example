<?php

namespace App\Domains\Role\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

/**
 * @property int $id
 * @property string $name
 * @method int getId()
 * @method string getName()
 * @method Role setName(string $name)
 */
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
}