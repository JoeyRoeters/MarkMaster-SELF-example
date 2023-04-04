<?php

require __DIR__ . '/../autoload.php';
require __DIR__ . '/../helpers.php';

session_start();

$app = new \SELF\src\Application();
$router = new \SELF\src\Http\Router();

$app->set(Kernel::class, fn () => new \SELF\src\Http\Kernel($app, $router));

$request = \SELF\src\Http\ServerRequest::fromGlobals();
