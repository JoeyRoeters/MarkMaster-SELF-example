<?php

namespace SELF\src\Helpers\Route;

use SELF\src\Http\Middleware\Middleware;
use SELF\src\Http\Route;

abstract class AbstractRoutable
{
    /**
     * @var Route[] $routes
     */
    protected array $routes = [];

    /**
     * @var Middleware[] $boundMiddleware
     */
    protected array $boundMiddleware = [];

    public function __construct()
    {
        $this->buildRoutes();
    }

    abstract public function buildRoutes(): void;

    public function make(Route $route): self
    {
        if ($this->hasBoundMiddleware()) {
            $route->addMiddleware($this->getBoundMiddleware());
        }

        $this->routes[] = $route;
        return $this;
    }

    public function setBoundMiddleware(Middleware | array $middleware): self
    {
        $this->boundMiddleware = is_array($middleware) ? $middleware : [$middleware];
        return $this;
    }

    public function getBoundMiddleware(): array
    {
        return $this->boundMiddleware;
    }

    public function hasBoundMiddleware(): bool
    {
        return ! empty($this->boundMiddleware);
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}