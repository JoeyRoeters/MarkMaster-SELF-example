<?php

namespace SELF\src\Auth;

use DateTime;
use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;
use SELF\src\Helpers\Interfaces\Auth\AuthRecordInterface;

/**
 * @property int $id
 * @property string $token
 * @property DateTime $expires_at
 */
class AuthRecord extends ActiveRecord implements AuthRecordInterface
{
    protected string $table = 'auth';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create($this->getIdentifierColumn(), ColumnType::INT),
            TableColumn::create($this->getTokenColumn()),
            TableColumn::create($this->getExpiresColumn(), ColumnType::DATETIME),
        ];
    }

    public function getIdentifierColumn(): string
    {
        return 'identifier';
    }

    public function getTokenColumn(): string
    {
        return 'token';
    }

    public function getExpiresColumn(): string
    {
        return 'expires_at';
    }
}