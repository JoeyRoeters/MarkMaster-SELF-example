<?php
namespace App\Domains\Auth\Repository;

use App\Domains\User\Repository\User;
use App\Domains\User\Repository\UserQuery;
use DateTime;
use SELF\src\Auth\AuthRecord;
use SELF\src\Helpers\Interfaces\Auth\AuthAppRecordInterface;

/**
 * Class Auth
 * @package App\Domains\Auth\Repository
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property DateTime $expires_at
 */
class Auth extends AuthRecord implements AuthAppRecordInterface
{
    public function getUser(): ?User
    {
        $user = UserQuery::create()->findPk($this->getIdentifier());
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }
}