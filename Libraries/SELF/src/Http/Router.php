<?php

namespace SELF\src\Http;

use SELF\src\Container;
use SELF\src\Exceptions\Route\RouteNotFoundException;
use SELF\src\Helpers\Request\RequestChain;
use SELF\src\Helpers\Route\AbstractRoutable;

class Router
{
    /**
     * @var Route[] $routes
     */
    private array $routes = [];

    private Container $container;

    public function __construct()
    {
        $this->container = Container::getInstance();
        $this->registerRoutes();
    }

    public function handleRoute(Request $request): mixed
    {
        $route = $this->matchRoute($request);

        return (new RequestChain())
            ->setRequest($request)
            ->setStages($route->getMiddleware())
            ->setFinally(fn (Request $request) => $this->sendToController($request, $route))
            ->handleChain();
    }

    private function sendToController(Request $request, Route $route): mixed
    {
        $targetClass = $this->container->resolve(
            $route->getTargetClass()
        );

        $targetMethod = $route->getTargetMethod();

        $params = $route->resolveInlineParameters($request->getUri());

        return $targetClass->$targetMethod(
            $request, $params
        );
    }

    private function matchRoute(Request $request): Route
    {
        foreach ($this->routes as $route) {
            if ($route->isMatch($request)) {
                return $route;
            }
        }

        throw new RouteNotFoundException(sprintf(
            'Route %s with method %s can not be found.',
            $request->getUri()->getPath(),
            $request->getMethod()->value,
        ));
    }

    private function registerRoutes(): void
    {
        $dirs = array_filter(glob(APP_DIR . '/Domains/*'), 'is_dir');

        foreach ($dirs as $dir) {
            $routeDir = "$dir/Routes";

            if (is_dir($routeDir)) {
                foreach (array_slice(scandir($routeDir), 2) as $file) {
                    $file = "$routeDir/$file";
                    $class = $this->getClassFromFile($file);

                    if ($class instanceof AbstractRoutable) {
                        array_push($this->routes, ...$class->getRoutes());
                    }
                }
            }
        }
    }

    private function getClassFromFile(string $file): object
    {
        $target = str_replace(
            '/', '\\', substr(str_replace([ROOT, '.php'], '', $file), 1)
        );
        return $this->container->resolve($target);
    }
}