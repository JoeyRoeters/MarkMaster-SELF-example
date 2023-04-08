<?php
namespace App\Domains\User\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\SqlColumn;

class User extends ActiveRecord
{
    protected string $table = 'users';

    public function tableColumns(): array
    {
        return [
            SqlColumn::create('id', \PDO::PARAM_INT, false),
            SqlColumn::create('name'),
            SqlColumn::create('email'),
            SqlColumn::create('password'),
            SqlColumn::create('created_at'),
            SqlColumn::create('updated_at'),
        ];
    }
}