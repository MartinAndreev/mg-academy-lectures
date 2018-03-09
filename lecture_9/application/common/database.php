<?php

$config = include_once 'config.php';

$db = new PDO("mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}", $config['username'], $config['password']);


if($db->errorCode()) {
    echo 'Problem connecting';
}