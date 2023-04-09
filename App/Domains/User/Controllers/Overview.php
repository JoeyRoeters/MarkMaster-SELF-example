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
//        $query->filterById([1, 2, 3], Criteria::NOT_IN);

        s_dump($query->find());
    }
}