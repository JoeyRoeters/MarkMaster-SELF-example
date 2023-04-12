<?php
namespace App\Domains\User\Controllers;

use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use SELF\src\Http\Request;

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
//        $user = new User();
//        $user->setName('appel');
//        $user->save();
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
        var_dump('this is root');
    }
}