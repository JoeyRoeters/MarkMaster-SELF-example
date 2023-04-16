<?php

namespace App\Domains\Exam\Repository;

use App\Domains\User\Repository\User;
use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByName(string $name, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByDate(\DateTime $date, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByCreatedAt(\DateTime $createdAt, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByUpdatedAt(\DateTime $updatedAt, Criteria $criteria = Criteria::EQUALS)
 */
class ExamQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return Exam::class;
    }


    public function filterByIsVisible(User $user): self
    {
        if ($user->isAdmin() || $user->isTeacher()) {
            return $this;
        }

        $ids = array_map(function ($class) {
            return $class->getId();
        }, $user->getClasses());

        $ids = array_map(
            function ($class) {
                return $class->getId();
            },
            ExamClassQuery::create()->filterByClassId($ids, Criteria::IN)->find()->getObjects()
        );

        $this->filterById($ids, Criteria::IN);

        return $this;
    }
}