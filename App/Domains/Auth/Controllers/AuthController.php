<?php

namespace App\Domains\Auth\Controllers;

use App\Authenticator;
use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use SELF\src\Exceptions\Environment\NotFoundException;
use SELF\src\Hash;
use SELF\src\Http\Controller;
use SELF\src\Http\Request;
use SELF\src\Http\Responses\MustacheResponse;
use SELF\src\Http\Responses\RedirectResponse;
use SELF\src\Http\Responses\Response;

class AuthController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->getIndexResponse(['login' => true]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws NotFoundException
     */
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

    /**
     * @return RedirectResponse
     * @throws NotFoundException
     */
    public function logout(): RedirectResponse
    {
        Authenticator::clear();
        return new RedirectResponse(
            environment('APP_URL') . '/login'
        );
    }

    /**
     * @param array $attributes
     * @return MustacheResponse
     */
    private function getIndexResponse(array $attributes = []): MustacheResponse
    {
        return new MustacheResponse('Authentication/login', $attributes);
    }
}