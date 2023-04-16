<?php

namespace App\Domains\Auth\Routes;

use App\Domains\Auth\Controllers\LoginController;
use App\Domains\Homepage\Controllers\HomepageController;
use App\Middleware\RedirectUnauthenticatedMiddleware;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Route;

class Web extends AbstractRoutable
{
    public function buildRoutes(): void
    {
        $this
            ->make(
                Route::GET(
                    path: '/',
                    targetClass: HomepageController::class,
                    targetMethod: 'index',
                    middleware: [new RedirectUnauthenticatedMiddleware('/login')]
                )
            )
            ->make(
                Route::GET(
                    path: '/login',
                    targetClass: LoginController::class,
                    targetMethod: 'index'
                )
            )
            ->make(
                Route::POST(
                    path: '/login',
                    targetClass: LoginController::class,
                    targetMethod: 'login'
                )
            );
    }
}