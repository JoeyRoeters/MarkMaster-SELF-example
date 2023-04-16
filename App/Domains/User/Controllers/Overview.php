<?php
namespace App\Domains\User\Controllers;

use App\Authenticator;
use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use SELF\src\Http\Request;
use SELF\src\Session;

class Overview
{
    public function __construct()
    {
//        $user = UserQuery::create()->findPk(1);
//        $user->setName('test');
//        $user->save();
//
//        s_dump($user);
//
//
//        s_dump($user);
//
//        $user->delete();
//        s_dump($user);
    }

    public function index(Request $request, array $params)
    {
        var_dump($params['user'] . ' inline parameter!');
    }

    public function test(Request $request)
    {
//        $user = new User();
//        $user->setName('tim');
//        $user->setEmail('tim@laptify.nl');
//        $user->save();

        /**
         * @var User $user
         */
        $user = UserQuery::create()->findPk(1);

//        Authenticator::login($user);

        sdd(Authenticator::user());
    }
}