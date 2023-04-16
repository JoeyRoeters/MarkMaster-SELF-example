<?php

namespace App\Domains\Authentication\Routes;

use App\Domains\Authentication\Controllers\AuthenticationController;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Route;

class Web extends AbstractRoutable {

    public function buildRoutes(): void
    {
        $this->make(
            Route::GET(
                path: '/',
                targetClass: AuthenticationController::class,
                targetMethod: 'index'
            )
        );
    }
}