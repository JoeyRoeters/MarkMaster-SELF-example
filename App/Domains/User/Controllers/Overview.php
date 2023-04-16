<?php
namespace App\Domains\User\Controllers;

use App\Authenticator;
use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;

class Overview
{
    public function __construct()
    {
    }

    public function index(Request $request, array $params)
    {
        $data = ['name' => 'appel'];
        $data['hobbies'] = ['gaming', 'coding', 'reading'];
        return new MustacheResponse('test', $data);
    }

    public function test(Request $request)
    {
//        $user = new User();
//        $user->setName('tim');
//        $user->setEmail('tim@laptify.nl');
//        $user->save();

//        /**
//         * @var User $user
//         */
//        $user = UserQuery::create()->findPk(1);
//
//        sdd(Authenticator::user());

        sdd('Dit werkt nu');
    }

    public function roletest()
    {
        sdd('Role ding');
    }
}