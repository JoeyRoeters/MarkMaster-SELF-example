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

function s_dump($var): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function sdd($var): void
{
    s_dump($var);
    die();
}

function array_map_with_keys(callable $callback, array $array): array {
    $result = [];

    foreach ($array as $key => $value) {
        $assoc = $callback($value, $key);

        foreach ($assoc as $mapKey => $mapValue) {
            $result[$mapKey] = $mapValue;
        }
    }

    return $result;
}