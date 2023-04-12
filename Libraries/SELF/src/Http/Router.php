<?php

namespace SELF\src\Http;

use SELF\src\Container;
use SELF\src\Helpers\Route\Routable;

class Router
{
    private static self $instance;

    private array $routes = [];

    public function __construct()
    {
        $this->registerRoutes();

        var_dump($this->routes);
    }

    public static function getInstance(): static
    {
        if (! isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function registerRoutes(): void
    {
        $dirs = array_filter(glob(APP . '/Domains/*'), 'is_dir');

        foreach ($dirs as $dir) {
            $routeDir = "$dir/Routes";

            if (is_dir($routeDir)) {
                foreach (array_slice(scandir($routeDir), 2) as $file) {
                    $file = "$routeDir/$file";
                    $class = new $file();
//
                    if ($class instanceof Routable) {
                        $this->routes[] = $class->getRoutes();
                    }
                }
            }
        }
    }
}