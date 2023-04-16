<?php

namespace App\Middleware;

use App\Authenticator;
use App\Domains\Role\Repository\Role;
use Closure;
use SELF\src\Http\Middleware\Middleware;
use SELF\src\Http\Request;

class UserHasRoleMiddleware extends Middleware
{
    /**
     * @var Role[] $roles
     */
    private readonly array $roles;

    public function __construct(array | Role $roles)
    {
        $this->roles = is_array($roles) ? $roles : [$roles];
    }

    public function handle(Request $request, Closure $next): mixed
    {
        $user = Authenticator::user();

        if ($user === null) {
            $this->fail();
        }

        foreach ($this->roles as $role) {
            if (! $user->hasRole($role)) {
                $this->fail();
            }
        }

        return $next($request);
    }

    private function fail(): void
    {
        die(
            sprintf('Unauthenticated: user does not have role %s', $this->role->name)
        );
    }
}