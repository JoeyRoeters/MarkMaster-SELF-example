<?php

namespace App\Traits;

use App\Authenticator;
use App\Domains\Role\Repository\RoleQuery;
use App\Domains\User\Repository\User;
use App\Enums\RoleEnum;

trait UserTrait
{
    protected ?User $user;

    public function __construct()
    {
        $user = Authenticator::user();
        if ($user instanceof User) {
            $this->user = $user;
        }
    }

}