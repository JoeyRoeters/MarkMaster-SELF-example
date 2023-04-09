<?php
namespace App\Domains\User\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

class User extends ActiveRecord
{
    protected string $table = 'users';

    public function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, false),
            TableColumn::create('name'),
            TableColumn::create('email'),
            TableColumn::create('password'),
            TableColumn::create('created_at', ColumnType::DATETIME),
            TableColumn::create('updated_at', ColumnType::DATETIME),
        ];
    }
}