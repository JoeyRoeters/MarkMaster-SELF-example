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
 * @property string $description
 * @property \DateTime $date
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 * @method int getId()
 * @method string getName()
 * @method string getDescription()
 * @method \DateTime getDate()
 * @method \DateTime getCreatedAt()
 * @method \DateTime getUpdatedAt()
 * @method Exam setName(string $name)
 * @method Exam setDescription(string $description)
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

    public function export(bool $full = false): array
    {
        $data = [
            'name' => '<a href="/exams/' . $this->getId() . '">' . $this->getName() . '</a>',
            'date' => $this->getDate(),
        ];

        if ($full) {
            $data['id'] = $this->getId();
            $data['name'] = $this->getName();
            $data['description'] = $this->getDescription();
            if ($this->getDate() instanceof \DateTime) {
                $data['date'] = $this->getDate()->format('d-m-Y H:i');
                $data['date_sql'] = $this->getDate()->format('Y-m-d');
                if ($this->getDate() < new \DateTime()) {
                    $data['status'] = ' Afgerond';
                    $data['status_class'] = 'success';
                } else {
                    $data['status'] = ' Nog te doen';
                    $data['status_class'] = 'warning';
                }
            }
        }

        return $data;
    }
}