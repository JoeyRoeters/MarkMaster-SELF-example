<?php
namespace App\Domains\Auth\Repository;

use DateTime;
use SELF\src\Auth\AuthRecord;

/**
 * Class Auth
 * @package App\Domains\Auth\Repository
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property DateTime $expires_at
 */
class Auth extends AuthRecord
{
    public function getIdentifierColumn(): string
    {
        return 'user_id';
    }
}