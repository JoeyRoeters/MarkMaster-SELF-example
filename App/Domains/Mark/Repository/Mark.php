<?php

namespace App\Domains\Mark\Repository;

use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;

/**
 * @property int $id
 * @property int $exam_id
 * @property int $student_id
 * @property int $mark
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @method int getId()
 * @method int getExamId()
 * @method int getStudentId()
 * @method int getMark()
 * @method \DateTime getCreatedAt()
 * @method \DateTime getUpdatedAt()
 * @method Mark setExamId(int $examId)
 * @method Mark setStudentId(int $studentId)
 * @method Mark setMark(int $mark)
 * @method Mark setCreatedAt(\DateTime $createdAt)
 * @method Mark setUpdatedAt(\DateTime $updatedAt)
 */
class Mark extends ActiveRecord
{
    protected string $table = 'marks';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('exam_id', ColumnType::INT, null, false),
            TableColumn::create('student_id', ColumnType::INT, null, false),
            TableColumn::create('mark', ColumnType::INT),
            TableColumn::create('created_at', ColumnType::DATETIME)->autoTimestamp(),
            TableColumn::create('updated_at', ColumnType::DATETIME)->autoTimestamp(),
        ];
    }

    public function getStudent(): User
    {
        return UserQuery::create()->findPk($this->getStudentId());
    }

    public function export(): array
    {
        return [
            'id' => $this->getId(),
            'exam_id' => $this->getExamId(),
            'student_id' => $this->getStudentId(),
            'student' => $this->getStudent()->getName(),
            'mark' => $this->getMark() / 10,
        ];
    }
}