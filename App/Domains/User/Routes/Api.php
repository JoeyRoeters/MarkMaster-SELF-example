<?php

namespace App\Domains\User\Routes;

use App\Domains\User\Controllers\Overview;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Middleware\ExampleMiddleware;
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
                    middleware: [ExampleMiddleware::class]
                )
            );
    }
}