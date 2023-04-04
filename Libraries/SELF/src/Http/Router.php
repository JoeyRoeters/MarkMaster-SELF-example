<?php

namespace SELF\src\Http;

class Router
{
    private static self $instance;

    private array $routes = [];

    public static function getInstance(): static
    {
        if (! isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function withRoute(Route $route): self
    {
        $this->routes[] = $route;
        return $this;
    }
}