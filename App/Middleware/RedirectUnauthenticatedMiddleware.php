<?php

namespace App\Middleware;

use App\Authenticator;
use Closure;
use SELF\src\Http\Middleware\Middleware;
use SELF\src\Http\Request;

class RedirectUnauthenticatedMiddleware extends Middleware
{
    private string $redirectPath;

    public function __construct(string $relativePath)
    {
        $this->redirectPath = environment('APP_URL') . $relativePath;
    }

    public function handle(Request $request, Closure $next): mixed
    {
        if (! Authenticator::check()) {
            header(sprintf('Location: %s', $this->redirectPath));
            die();
        }

        return $next($request);
    }
}