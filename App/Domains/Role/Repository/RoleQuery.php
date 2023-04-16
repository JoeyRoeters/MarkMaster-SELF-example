<?php

namespace App\Domains\Role\Repository;

use App\Domains\User\Repository\User;
use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int $id, Criteria $criteria = Criteria::EQUALS)
 * @method self
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
}