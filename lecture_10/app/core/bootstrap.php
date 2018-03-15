<?php

session_start();
spl_autoload_register(function($class) {

    $directories = [
        CORE_PATH,
        APP_PATH . 'controllers/',
        APP_PATH . 'models/',
    ];

    $path = ucwords($class);

    foreach ($directories as $dir) {
        if (file_exists($dir . $path . '.php')) {
            require_once $dir . $path . '.php';
            break;
        }
    }
});

$registry = Registry::instance();
$registry->getDispatcher()->dispatch();
