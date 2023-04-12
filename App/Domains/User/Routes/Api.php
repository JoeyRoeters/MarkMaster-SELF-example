<?php

namespace App\Domains\User\Routes;

use App\Domains\User\Controllers\Overview;
use SELF\src\Helpers\Enums\MethodEnum;
use SELF\src\Helpers\Route\Routable;
use SELF\src\Http\Route;

class Api extends Routable {
    public function __invoke()
    {
        $this->make(
            new Route(
                MethodEnum::GET,
                '/user',
                Overview::class,
                'index',
            )
        );
    }
}