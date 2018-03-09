<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__FILE__) . '/');
define('SYSTEM_PATH', ROOT_PATH . 'common/');
define('TEMPLATE_PATH', ROOT_PATH . 'templates/');
define('ACTIONS_PATH', ROOT_PATH . 'actions/');
require_once SYSTEM_PATH . 'init.php';

