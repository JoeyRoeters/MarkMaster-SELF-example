<?php

namespace SELF\src\Exceptions\Environment;

class NotFoundException extends EnvironmentException
{
    public function __construct(string $key)
    {
        parent::__construct("Environment variable {$key} is undefined.");
    }
}