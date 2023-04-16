<?php
namespace App\Domains\Exam\Repository;

use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByUserId(int | int[] $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByExamId(int | int[] $id, Criteria $criteria = Criteria::EQUALS)
 */
class ExamUserQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return ExamUser::class;
    }
}