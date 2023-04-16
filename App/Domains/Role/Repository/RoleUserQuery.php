<?php

namespace App\Domains\Role\Repository;

use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByRoleId(int $id, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByUserId(int $id, Criteria $criteria = Criteria::EQUALS)
 */
class RoleUserQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return RoleUser::class;
    }
}