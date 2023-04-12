<?php

namespace SELF\src\Helpers\Route;

use SELF\src\Http\Route;

abstract class AbstractRoutable
{
    /**
     * @var Route[] $routes
     */
    protected array $routes = [];

    public function __construct()
    {
        $this->buildRoutes();
    }

    abstract public function buildRoutes(): void;

    public function make(Route $route): self
    {
        $this->routes[] = $route;
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}