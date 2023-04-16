<?php

namespace App\Domains\Auth\Routes;

use App\Domains\Auth\Controllers\AuthController;
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
                    path: '/login',
                    targetClass: AuthController::class,
                    targetMethod: 'index',
                )
            )
            ->make(
                Route::POST(
                    path: '/login',
                    targetClass: AuthController::class,
                    targetMethod: 'login',
                )
            )
            ->make(
                Route::GET(
                    path: '/logout',
                    targetClass: AuthController::class,
                    targetMethod: 'logout',
                )
            );
    }
}