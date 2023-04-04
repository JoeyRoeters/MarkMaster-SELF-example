<?php

namespace App;

use SELF\src\Http\Kernel as BaseKernel;
use SELF\src\Http\Middleware\ExampleMiddleware;

class Kernel extends BaseKernel
{
    protected array $middleware = [
        ExampleMiddleware::class
    ];
}