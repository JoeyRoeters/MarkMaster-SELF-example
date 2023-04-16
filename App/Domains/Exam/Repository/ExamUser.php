<?php

namespace App\Domains\Exam\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

/**
 * @method self setExamId(int $id)
 * @method self setUserId(int $id)
 */
class ExamUser extends ActiveRecord
{
    protected string $table = 'exam_user';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('exam_id', ColumnType::INT),
            TableColumn::create('user_id', ColumnType::INT),
            TableColumn::create('created_at', ColumnType::DATETIME)->autoTimestamp(),
            TableColumn::create('updated_at', ColumnType::DATETIME)->autoTimestamp(),
        ];
    }
}