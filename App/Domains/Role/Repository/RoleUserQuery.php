<?php

namespace App\Domains\Role\Repository;

use SELF\src\HelixORM\Query\AbstractQuery;

class RoleUserQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return RoleUser::class;
    }
}