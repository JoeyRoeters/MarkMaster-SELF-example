<?php

spl_autoload_register(function (string $className): void {
    $ds = DIRECTORY_SEPARATOR;
    $dirs = [
        __DIR__,
        __DIR__ . '/Libraries',
    ];

    foreach ($dirs as $dir) {
        $className = str_replace('\\', $ds, $className);
        $file = "$dir$ds$className.php";

        if (is_readable($file)) {
            require_once $file;
        }
    }
});