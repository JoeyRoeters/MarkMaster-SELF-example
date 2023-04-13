<?php

namespace SELF\src\Http\Middleware;

use Closure;
use SELF\src\Http\Request;

class ExampleMiddleware extends Middleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }
}