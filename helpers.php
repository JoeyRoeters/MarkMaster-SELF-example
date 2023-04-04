<?php

// define paths
define('ROOT', __DIR__);
define('APP', ROOT . '/App');
define('LIB', ROOT . '/Libraries');
define('PUBLIC', ROOT . '/Public');
define('STORAGE', ROOT . '/storage');
define('ASSETS', ROOT . '/assets');

function environment(string $key): string
{
    return SELF\src\Environment::getInstance()->get($key);
}