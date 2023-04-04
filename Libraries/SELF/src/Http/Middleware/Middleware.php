<?php

namespace SELF\src\Http\Middleware;

use Closure;
use SELF\src\Helpers\Interfaces\Chain\RequestChainableInterface;
use SELF\src\Http\Request;

abstract class Middleware implements RequestChainableInterface
{
    abstract public function handle(Request $request, Closure $next): mixed;
}
