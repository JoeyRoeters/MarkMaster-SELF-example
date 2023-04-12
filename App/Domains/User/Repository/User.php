<?php
namespace App\Domains\User\Repository;

use DateTime;
use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;


/**
 * Class User
 * @package App\Domains\User\Repository
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $created_at
 * @property string $updated_at
 * @method string|null getName()
 * @method string|null getEmail()
 * @method string|null getPassword()
 * @method DateTime|null getCreatedAt()
 * @method DateTime|null getUpdatedAt()
 * @method User setName(string $name)
 * @method User setEmail(string $email)
 * @method User setPassword(string $password)
 * @method User setCreatedAt(DateTime $createdAt)
 * @method User setUpdatedAt(DateTime $updatedAt)
 */
class User extends ActiveRecord
{
    protected string $table = 'users';

    public function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('name'),
            TableColumn::create('email'),
            TableColumn::create('password'),
            TableColumn::create('created_at', ColumnType::DATETIME)->autoTimestamp(),
            TableColumn::create('updated_at', ColumnType::DATETIME)->autoTimestamp(),
        ];
    }
}