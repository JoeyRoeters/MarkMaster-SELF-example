<?php

require __DIR__ . '/../autoload.php';

$class = new \App\Domains\User\Controllers\Overview();

$app = new \SELF\src\Application();

$router = new \SELF\src\Http\Router();

$app->set(Kernel::class, fn () => new \SELF\src\Http\Kernel($app, $router));