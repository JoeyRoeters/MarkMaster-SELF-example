<?php

namespace App\Domains\Exam\Repository;

use App\Domains\Class\Repository\StudentClass;
use App\Domains\Class\Repository\StudentClassQuery;
use SELF\src\HelixORM\HelixObjectCollection;
use SELF\src\HelixORM\Record\ActiveRecord;
use SELF\src\HelixORM\TableColumn;
use SELF\src\Helpers\Enums\HelixORM\ColumnType;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @property int $id
 * @property string $name
 * @property \DateTime $date
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @method int getId()
 * @method string getName()
 * @method \DateTime getDate()
 * @method \DateTime getCreatedAt()
 * @method \DateTime getUpdatedAt()
 * @method Exam setName(string $name)
 * @method Exam setDate(\DateTime $date)
 * @method Exam setCreatedAt(\DateTime $createdAt)
 * @method Exam setUpdatedAt(\DateTime $updatedAt)
 */
class Exam extends ActiveRecord
{
    protected string $table = 'exams';

    protected function tableColumns(): array
    {
        return [
            TableColumn::create('id', ColumnType::INT, null, false)->setPrimaryKey(true),
            TableColumn::create('name'),
            TableColumn::create('description'),
            TableColumn::create('date', ColumnType::DATETIME),
            TableColumn::create('created_at', ColumnType::DATETIME)->autoTimestamp(),
            TableColumn::create('updated_at', ColumnType::DATETIME)->autoTimestamp(),
        ];
    }

    /**
     * @return StudentClass[]
     */
    public function classes(): array
    {
        /** @var ExamClass[] $pivots */
        $pivots = ExamClassQuery::create()
            ->filterByExamId($this->id)
            ->find()
            ->get();

        return StudentClassQuery::create()
            ->filterById(array_pluck('id', $pivots), Criteria::IN)
            ->find()
            ->get();
    }

    public function syncClassIds(array $classIds): self
    {
        ExamClassQuery::create()
            ->filterByClassId($classIds, Criteria::IN)
            ->filterByExamId($this->id)
            ->delete();

        foreach ($classIds as $classId) {
            (new ExamClass())
                ->setClassId($classId)
                ->setExamId($this->id)
                ->save();
        }

        return $this;
    }

    public function export(): array
    {
        return [
            'name' => '<a href="/exam/' . $this->getId() . '">' . $this->getName() . '</a>',
            'date' => $this->getDate(),
        ];
    }
}