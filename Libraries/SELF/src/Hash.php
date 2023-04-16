<?php

namespace SELF\src;

class Hash
{
    public static function make(string $hashable, string $algo = PASSWORD_DEFAULT): string
    {
        return password_hash($hashable, $algo);
    }

    public static function check(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }
}