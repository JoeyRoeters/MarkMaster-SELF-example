<?php

namespace App\Domains\Class\Repository;

use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

/**
 * @property int $id
 * @property int $student_class_id
 * @property int $student_id
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @method int getId()
 * @method int getStudentClassId()
 * @method int getStudentId()
 * @method \DateTime getCreatedAt()
 * @method \DateTime getUpdatedAt()
 * @method StudentClassToStudent setStudentClassId(int $studentClassId)
 * @method StudentClassToStudent setStudentId(int $studentId)
 * @method StudentClassToStudent setCreatedAt(\DateTime $createdAt)
 * @method StudentClassToStudent setUpdatedAt(\DateTime $updatedAt)
 */
class StudentClassToStudent extends ActiveRecord
{
    protected string $table = 'student_classes_to_student';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('student_class_id', ColumnType::INT, null, false),
            TableColumn::create('student_id', ColumnType::INT, null, false),
            TableColumn::create('created_at', ColumnType::DATETIME)->autoTimestamp(),
            TableColumn::create('updated_at', ColumnType::DATETIME)->autoTimestamp(),
        ];
    }

    public function getClass(): ?StudentClass
    {
        return StudentClassQuery::create()->findPk($this->getStudentClassId());
    }
}