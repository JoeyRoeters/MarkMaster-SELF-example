<?php

namespace App\Domains\Exam\Repository;

use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByClassId(int $classId, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByExamId(int $examId, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByCreatedAt(\DateTime $createdAt, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByUpdatedAt(\DateTime $updatedAt, Criteria $criteria = Criteria::EQUALS)
 */
class ExamClassQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return ExamClass::class;
    }
}