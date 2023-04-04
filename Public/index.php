<?php

require __DIR__ . '/../autoload.php';

session_start();
$class = new \App\Domains\User\Controllers\Overview();

$app = new \SELF\src\Application();

$router = new \SELF\src\Http\Router();

$app->set(Kernel::class, fn () => new \SELF\src\Http\Kernel($app, $router));

$request = \SELF\src\Http\ServerRequest::fromGlobals();