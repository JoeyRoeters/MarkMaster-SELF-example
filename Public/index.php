<?php

use App\Kernel;
use SELF\src\Application;
use SELF\src\Http\Router;
use SELF\src\Http\ServerRequest;

require __DIR__ . '/../autoload.php';
require __DIR__ . '/../helpers.php';

session_start();

$app = new Application();
$router = new Router();

$app->set(Kernel::class, fn () => new Kernel($app, $router));

$request = ServerRequest::fromGlobals();
$kernel = $app->get(Kernel::class);

$kernel->handleRequest($request);
