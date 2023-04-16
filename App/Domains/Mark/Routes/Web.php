<?php

namespace App\Domains\Mark\Routes;

use App\Domains\Mark\Controllers\MarkController;
use App\Domains\Role\Repository\RoleQuery;
use App\Enums\RoleEnum;
use App\Middleware\UserHasRoleMiddleware;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Route;

class Web extends AbstractRoutable
{
    public function buildRoutes(): void
    {
        $this->make(
            Route::GET(
                path: '/marks',
                targetClass: MarkController::class,
                targetMethod: 'index',
                middleware: [new UserHasRoleMiddleware(RoleQuery::findOrCreate(RoleEnum::STUDENT))]
            )
        );
    }

}