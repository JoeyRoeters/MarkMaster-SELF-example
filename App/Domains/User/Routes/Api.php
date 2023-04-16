<?php

namespace App\Domains\User\Routes;

use App\Domains\Role\Repository\Role;
use App\Domains\Role\Repository\RoleQuery;
use App\Domains\User\Controllers\Overview;
use App\Enums\RoleEnum;
use App\Middleware\RedirectUnauthenticatedMiddleware;
use App\Middleware\UserHasRoleMiddleware;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Route;

class Api extends AbstractRoutable {
    public function buildRoutes(): void
    {
        $this
            ->make(
                Route::GET('/', Overview::class, 'test')
            )
            ->make(
                Route::GET(
                    path: '/users/{user}',
                    targetClass: Overview::class,
                    targetMethod: 'index',
                    middleware: [
                        new RedirectUnauthenticatedMiddleware('/login'),
                        new UserHasRoleMiddleware(RoleQuery::findOrCreate(RoleEnum::STUDENT)),
                    ]
                )
            );
    }
}