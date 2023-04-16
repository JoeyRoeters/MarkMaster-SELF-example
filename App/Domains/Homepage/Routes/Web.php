<?php

namespace App\Domains\Homepage\Routes;

use App\Domains\Homepage\Controllers\HomepageController;
use App\Middleware\RedirectUnauthenticatedMiddleware;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Route;

class Web extends AbstractRoutable
{
    public function buildRoutes(): void
    {
        $this->make(
            Route::GET(
                path: '/',
                targetClass: HomepageController::class,
                targetMethod: 'index',
                middleware: [new RedirectUnauthenticatedMiddleware('/login')]
            )
        );
    }
}