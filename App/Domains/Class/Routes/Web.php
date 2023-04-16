<?php

namespace App\Domains\Class\Routes;

use App\Domains\Class\Controllers\ClassController;
use App\Domains\Role\Repository\RoleQuery;
use App\Enums\RoleEnum;
use App\Middleware\UserHasRoleMiddleware;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Route;

class Web extends AbstractRoutable
{
    public function buildRoutes(): void
    {
        $middleware = [new UserHasRoleMiddleware([
            RoleQuery::findOrCreate(RoleEnum::TEACHER),
            RoleQuery::findOrCreate(RoleEnum::ADMIN),
        ])];

        $this
            ->setBoundMiddleware($middleware)
            ->make(
                Route::GET(
                    path: '/classes',
                    targetClass: ClassController::class,
                    targetMethod: 'index',
                )
            )
            ->make(
                Route::GET(
                    path: '/classes/create',
                    targetClass: ClassController::class,
                    targetMethod: 'indexNewOrEdit',
                )
            )
            ->make(
                Route::POST(
                    path: '/classes/create',
                    targetClass: ClassController::class,
                    targetMethod: 'submitNewOrEdit',
                )
            )
            ->make(
                Route::GET(
                    path: '/classes/{class}/edit',
                    targetClass: ClassController::class,
                    targetMethod: 'indexNewOrEdit'
                )
            )
            ->make(
                Route::POST(
                    path: '/classes/{class}/edit',
                    targetClass: ClassController::class,
                    targetMethod: 'submitNewOrEdit',
                )
            );
    }
}