<?php

namespace SELF\src\Http\Middleware;

use Closure;
use SELF\src\Http\Request;
use SELF\src\Http\Response;

class ExampleMiddleware extends Middleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        var_dump('Hallo dit is een example');

        return $next($request);
    }
}