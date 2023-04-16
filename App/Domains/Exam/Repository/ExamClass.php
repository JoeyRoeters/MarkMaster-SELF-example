<?php

namespace App\Domains\Exam\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

/**
 * @property int $id
 * @property int $class_id
 * @property int $exam_id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @method int getId()
 * @method int getClassId()
 * @method int getExamId()
 * @method \DateTime getCreatedAt()
 * @method \DateTime getUpdatedAt()
 * @method ExamClass setClassId(int $classId)
 * @method ExamClass setExamId(int $examId)
 * @method ExamClass setCreatedAt(\DateTime $createdAt)
 * @method ExamClass setUpdatedAt(\DateTime $updatedAt)
 */
class ExamClass extends ActiveRecord
{
    protected string $table = 'exam_classes';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('class_id', ColumnType::INT),
            TableColumn::create('exam_id', ColumnType::INT),
            TableColumn::create('created_at', ColumnType::DATETIME)->autoTimestamp(),
            TableColumn::create('updated_at', ColumnType::DATETIME)->autoTimestamp(),
        ];
    }
}