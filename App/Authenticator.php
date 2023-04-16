<?php

namespace App;

use App\Domains\Auth\Repository\Auth;
use App\Domains\Auth\Repository\AuthQuery;
use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use SELF\src\Auth\AuthRecord;
use SELF\src\Authenticator as BaseAuthenticator;

class Authenticator extends BaseAuthenticator
{
    public static function user(): ?User
    {
        $instance = self::getInstance();

        /**
         * @var Auth | null $authRecord
         */
        $authRecord = $instance->getAuthRecordFromSession();
        if ($authRecord === null) {
            return null;
        }

        /**
         * @var User | null $user
         */
        $user = UserQuery::create()->findPk(
            $authRecord->identifier
        );

        return $user;
    }

    public function getAuthModel(): AuthRecord
    {
        return new Auth();
    }

    public function getAuthQuery(): AuthQuery
    {
        return new AuthQuery();
    }
}