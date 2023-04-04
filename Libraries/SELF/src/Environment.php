<?php
namespace SELF\src;

use SELF\src\Exceptions\Environment\EnvironmentException;
use SELF\src\Exceptions\Environment\NotFoundException;

class Environment
{
    private static $instance;

    private array $env;

    private function __construct()
    {
        $path = ROOT . '/.env';
        if (!file_exists($path)) {
            throw new EnvironmentException('Environment not found. Please create a .env file in the root directory.');
        }

        $this->env = parse_ini_file($path);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get(string $key): string
    {
        if (isset($this->env[$key])) {
            return $this->env[$key];
        }

        throw new NotFoundException($key);
    }
}