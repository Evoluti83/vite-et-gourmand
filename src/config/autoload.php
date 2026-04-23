<?php

spl_autoload_register(function (string $className): void {
    $dirs = [
        __DIR__ . '/../entities/',
        __DIR__ . '/../repositories/',
        __DIR__ . '/../services/',
    ];

    foreach ($dirs as $dir) {
        $file = $dir . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});