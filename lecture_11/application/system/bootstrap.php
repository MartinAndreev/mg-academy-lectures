<?php

// Load the dependency infection
require_once SYSTEM_PATH . 'DI.php';
require_once SYSTEM_PATH . 'core/Loader.php';
require_once ROOT_PATH . 'vendor/autoload.php';


// Load the default instance of the DI
$di = System\DI::getDefault();
$di->set('loader', function() {
    return \System\Core\Loader::initLoader();
}, true);

// Start the autoloader
$di->loader->register();

// Load the config
$di->set('config', function() {
    $config = new System\Core\Config();
    $config->load('config');

    return $config;
}, true);

$di->set('db', function() use($di) {
    $di->config->load('database');

    $database = new \Medoo\Medoo([
        'database_type' => $di->config->get('database.type'),
        'database_name' => $di->config->get('database.database'),
        'server' => $di->config->get('database.host'),
        'username' => $di->config->get('database.username'),
        'password' => $di->config->get('database.password'),
    ]);

    return $database;
}, true);

$di->set('request', function() {
    return Symfony\Component\HttpFoundation\Request::createFromGlobals();
}, true);

$di->set('responce', function() {
    $responce = new Symfony\Component\HttpFoundation\Response();

    return $responce;
});

$di->set('view', function() {
    $loader = new Twig_Loader_Filesystem(APP_PATH . 'view/');
    $twig = new Twig_Environment($loader, array(
        'cache' => APP_PATH . 'cache/compiled/',
        'auto_reload' => true,
    ));

    return $twig;
});

$di->set('uri', function() use ($di) {
    $uri = new System\Core\Uri();
    $uri->setDI($di);
    $uri->init();

    return $uri;
}, true);

$di->set('dispatcher', function() use ($di) {
    $di->config->load('routing');

    return new System\Core\Dispatcher($di);
}, true);

$di->set('session', function() use ($di) {
    $session = new System\Core\Session();
    $session->setDI($di);

    return $session;
}, true);

$di->dispatcher->dispatch();
