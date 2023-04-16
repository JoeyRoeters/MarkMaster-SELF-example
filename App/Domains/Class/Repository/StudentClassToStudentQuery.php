<?php

namespace App\Domains\Class\Repository;

use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByStudentId(int $studentId, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByClassId(int $classId, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByCreatedAt(\DateTime $createdAt, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByUpdatedAt(\DateTime $updatedAt, Criteria $criteria = Criteria::EQUALS)
 */
class StudentClassToStudentQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return StudentClassToStudent::class;
    }
}