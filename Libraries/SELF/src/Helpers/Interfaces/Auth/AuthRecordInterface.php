<?php

namespace SELF\src\Helpers\Interfaces\Auth;

interface AuthRecordInterface
{
    public function getIdentifierColumn(): string;

    public function getTokenColumn(): string;

    public function getExpiresColumn(): string;
}