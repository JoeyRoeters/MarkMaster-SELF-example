<?php

namespace SELF\src\Helpers\Interfaces\Chain;

use Closure;
use SELF\src\Http\Request;

interface RequestChainableInterface
{
    public function handle(Request $request, Closure $next): mixed;
}