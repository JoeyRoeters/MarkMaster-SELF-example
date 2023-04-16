<?php

namespace App;

use SELF\src\Http\Kernel as BaseKernel;
use SELF\src\Http\Middleware\ExampleMiddleware;

class Kernel extends BaseKernel
{
    public function getMiddleware(): array
    {
        return [
            new ExampleMiddleware(),
        ];
    }
}