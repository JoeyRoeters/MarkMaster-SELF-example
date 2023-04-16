<?php

// define paths
define('ROOT', __DIR__);
define('APP_DIR', ROOT . '/App');
define('LIB_DIR', ROOT . '/Libraries');
define('PUBLIC_DIR', ROOT . '/Public');
define('STORAGE_DIR', ROOT . '/storage');
define('ASSETS_DIR', ROOT . '/assets');
define('BASE_URL', environment('APP_URL'));

function environment(string $key, mixed $fallback = null): string
{
    try {
        return SELF\src\Environment::getInstance()->get($key);
    } catch (\SELF\src\Exceptions\Environment\NotFoundException $e) {
        if ($fallback === null) {
            throw $e;
        }

        return $fallback;
    }
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