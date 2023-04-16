<?php

namespace App\Domains\Class\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

/**
 * @property int $id
 * @property string $name
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @method int getId()
 * @method string getName()
 * @method \DateTime getCreatedAt()
 * @method \DateTime getUpdatedAt()
 * @method StudentClass setName(string $name)
 * @method StudentClass setCreatedAt(\DateTime $createdAt)
 * @method StudentClass setUpdatedAt(\DateTime $updatedAt)
 */
class StudentClass extends ActiveRecord
{
    protected string $table = 'student_classes';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('name'),
            TableColumn::create('created_at', ColumnType::DATETIME)->autoTimestamp(),
            TableColumn::create('updated_at', ColumnType::DATETIME)->autoTimestamp(),
        ];
    }

}