<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/configs/env.php';
require_once __DIR__ . '/configs/helper.php';
require_once __DIR__ . '/configs/database.php';

spl_autoload_register(function (string $class): void {
    $paths = [
        PATH_CONTROLLER . $class . '.php',
        PATH_MODEL . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

require_once __DIR__ . '/routes/index.php';

