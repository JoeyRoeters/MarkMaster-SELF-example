<?php

namespace App\Domains\User\Routes;

use App\Domains\User\Controllers\Overview;
use SELF\src\Helpers\Route\AbstractRoutable;
use SELF\src\Http\Route;

class Api extends AbstractRoutable {
    public function buildRoutes(): void
    {
        $this
            ->make(
                Route::GET('/users', Overview::class, 'index')
            );
    }
}