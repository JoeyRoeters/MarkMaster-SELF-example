<?php

namespace App\Domains\User\Repository;

use SELF\src\HelixORM\Query\AbstractQuery;
use SELF\src\Helpers\Enums\HelixORM\Criteria;

/**
 * @method self filterById(int $int, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByName(?string $name, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByEmail(?string $email, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByPassword(?string $password, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByCreatedAt(?\DateTimeInterface $createdAt, Criteria $criteria = Criteria::EQUALS)
 * @method self filterByUpdatedAt(?\DateTimeInterface $updatedAt, Criteria $criteria = Criteria::EQUALS)
 * @method self orderByName(Criteria $criteria = Criteria::ASC)
 * @method self orderByEmail(Criteria $criteria = Criteria::ASC)
 * @method self orderByPassword(Criteria $criteria = Criteria::ASC)
 * @method self orderByCreatedAt(Criteria $criteria = Criteria::ASC)
 * @method self orderByUpdatedAt(Criteria $criteria = Criteria::ASC)
 */
class UserQuery extends AbstractQuery
{
    public function getModel(): string
    {
        return User::class;
    }
}