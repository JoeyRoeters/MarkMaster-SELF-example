<?php

namespace SELF\src\Helpers\Route;

use SELF\src\Http\Route;

class Routable
{
    /**
     * @var Route[] $routes
     */
    protected array $routes = [];

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