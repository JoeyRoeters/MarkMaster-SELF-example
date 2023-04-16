<?php

namespace SELF\src\Helpers\Interfaces\Auth;

interface AuthenticatableInterface
{
    /**
     * Unique field like username or email.
     *
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * Should be a hashed password.
     *
     * @return string
     */
    public function getHashedPassword(): string;
}