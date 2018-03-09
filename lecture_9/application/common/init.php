<?php

require_once 'database.php';
require_once 'functions.php';

session_start();

$action = (isset($_GET['action'])) ? $_GET['action'] : 'index';
 
$file = ACTIONS_PATH . $action . '.php';

if(file_exists($file)) {
    include_once $file;
}