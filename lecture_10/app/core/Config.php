<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Config {

    protected $_config = [];

    function __construct() {
        $this->_config = include_once APP_PATH . 'config/config.php';
    }

    public function get($key) {
        return (isset($this->_config[$key])) ? $this->_config[$key] : false;
    }

    public function __get($key) {
        return $this->get($key);
    }

}
