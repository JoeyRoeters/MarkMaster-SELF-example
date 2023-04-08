<?php
namespace App\Domains\User\Controllers;

use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

class Overview
{
    public function __construct()
    {
        $query = UserQuery::create();
        $query->filterById(1);
        $query->orderByEmail(Criteria::DESC);
        $query->limit(1);

        s_dump($query->find());
    }
}