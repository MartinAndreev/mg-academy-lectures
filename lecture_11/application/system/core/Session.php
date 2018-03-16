<?php

namespace System\Core;

class Session implements Interfaces\DIInjectable {

    function __construct() {
        session_start();
    }

    public function getDI() {
        
    }

    public function setDI(\System\DI $di) {
        
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
    }

    function __destruct() {
        session_unset();
        session_destroy();
    }

}
