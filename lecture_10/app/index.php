<?php

/**
 * Index path
 */
define('ROOT_PATH', dirname(__FILE__) . '/');

define('CORE_PATH', ROOT_PATH . 'core/');

define('APP_PATH', ROOT_PATH . 'application/');

define('ENVIRONMENT', 'dev');

if (defined('ENVIRONMENT') && ENVIRONMENT == 'dev') {
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 'off');
    error_reporting(0);
}

require_once CORE_PATH . 'bootstrap.php';
