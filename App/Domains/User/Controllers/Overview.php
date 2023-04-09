<?php
namespace App\Domains\User\Controllers;

use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;

class Overview
{
    public function __construct()
    {
        $user = UserQuery::create()->findPk(1);
        $user->setName('test');
        $user->save();

        s_dump($user);

        $user = new User();
        $user->setName('appel');
        $user->save();

        s_dump($user);

        $user->delete();
//        s_dump($user);
    }
}