<?php

namespace App\Domains\Role\Repository;

use App\Domains\User\Repository\User;
use App\Enums\RoleEnum;
use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByName(string $name, Criteria $criteria = Criteria::EQUALS)
 */
class RoleQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return Role::class;
    }

    public function getUserRoles(User $user)
    {

    }

    public static function findOrCreate(RoleEnum $roleEnum): Role
    {
        $role = RoleQuery::create()
            ->filterByName($roleEnum->value)
            ->findOne();

        if ($role === null) {
            $role = new Role();
            $role->set('name', $roleEnum->value);
            $role->save();
        }

        return $role;
    }
}