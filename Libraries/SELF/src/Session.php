<?php

namespace SELF\src;

class Session
{
    protected static self $instance;

    public function __construct()
    {
        if (! isset($_SESSION)) {
            session_start();
        }
    }

    public static function getInstance(): static
    {
        if (! isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function get(string $key): ?string
    {
        return $_SESSION[$key];
    }

    public function set(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }
}