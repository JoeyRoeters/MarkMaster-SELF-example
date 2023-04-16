<?php

namespace App\Domains\Auth\Repository;

use DateTimeInterface;
use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterByToken(string $token, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByExpiresAt(DateTimeInterface $expiresAt, Criteria $criteria = Criteria::EQUALS)
 */
class AuthQuery extends AbstractQuery
{
    /**
     * @return string
     */
    public function getModel(): string
    {
        return Auth::class;
    }
}