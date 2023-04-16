<?php

namespace App\Middleware;

use App\Authenticator;
use App\Domains\Role\Repository\Role;
use Closure;
use SELF\src\Http\Middleware\Middleware;
use SELF\src\Http\Request;

class UserHasRoleMiddleware extends Middleware
{
    public function __construct(private readonly Role $role)
    {}

    public function handle(Request $request, Closure $next): mixed
    {
        $user = Authenticator::user();

        if ($user === null || ! $user->hasRole($this->role)) {
            die(
                sprintf('Unauthenticated: user does not have role %s', $this->role->name)
            );
        }

        return $next($request);
    }
}