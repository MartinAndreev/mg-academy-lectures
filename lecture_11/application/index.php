<?php

/**
 * Index path
 */
define('ROOT_PATH', dirname(__FILE__) . '/');

define('SYSTEM_PATH', ROOT_PATH . 'system/');

define('APP_PATH', ROOT_PATH . 'app/');

define('ENVIRONMENT', 'dev');

if (defined('ENVIRONMENT') && ENVIRONMENT == 'dev') {
    ini_set('display_errors', 'on');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 'off');
    error_reporting(0);
}

require_once SYSTEM_PATH . 'bootstrap.php';
