<?php

namespace App\Domains\Auth\Controllers;

use App\Authenticator;
use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use SELF\src\Hash;
use SELF\src\Http\Controller;
use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;
use SELF\src\Http\Responses\RedirectResponse;
use SELF\src\Http\Responses\Response;

class AuthController extends Controller
{
    public function index(): Response
    {
        return $this->getIndexResponse(['login' => true]);
    }

    public function login(Request $request): Response
    {
        $attributes = $request->postParameters();

        /**
         * @var User | null $user
         */
        $user = UserQuery::create()
            ->filterByEmail($attributes['email'])
            ->findOne();

        if ($user === null || ! Hash::check($attributes['password'], $user->password)) {
            return $this->getIndexResponse(['loginFailed' =>  true]);
        }

        Authenticator::login($user);

        return new RedirectResponse(
            environment('APP_URL')
        );
    }

    public function logout(): RedirectResponse
    {
        Authenticator::clear();
        return new RedirectResponse(
            environment('APP_URL') . '/login'
        );
    }

    private function getIndexResponse(array $attributes = []): MustacheResponse
    {
        return new MustacheResponse('Authentication/login', $attributes);
    }
}