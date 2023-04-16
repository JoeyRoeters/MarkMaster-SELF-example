<?php

namespace App\Domains\Class\Repository;

use App\Domains\User\Repository\User;
use SELF\src\HelixORM\HelixObjectCollection;
use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int | int[] $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByName(string $name, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByCreatedAt(\DateTime $createdAt, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByUpdatedAt(\DateTime $updatedAt, Criteria $criteria = Criteria::EQUALS)
 */
class StudentClassQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return StudentClass::class;
    }

    /**
     * @param User $user
     * @return HelixObjectCollection
     */
    public function getStudentClasses(User $user): HelixObjectCollection
    {
        $classIds = StudentClassToStudentQuery::create()
            ->filterByStudentId($user->id)
            ->find();

        $classIds = array_map(function ($class) {
            return $class->class_id;
        }, $classIds->getObjects());

        return $this->filterById($classIds, Criteria::IN)->find();
    }
}