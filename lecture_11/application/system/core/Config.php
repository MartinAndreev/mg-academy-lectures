<?php

namespace System\Core;

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

/**
 * Used for the loader for locate needed classes for the system
 * 
 * @author Martin Andreev <martin.andreev92@gmail.coom>
 * @version 1.0
 * @since 1.0
 * @package com.mgacademy.lectures.framework
 */
class Config {

    /**
     * Stores the configuration
     * @var \Adbar\Dot 
     */
    protected $_config = null;

    function __construct() {
        $this->_config = new \Adbar\Dot();
    }

    public function load($file, $key = '', $path = '') {

        if ($path == '') {
            $path = APP_PATH . 'config/';
        }

        if ($key == '') {
            $key = pathinfo($file, PATHINFO_FILENAME);
        }

        if (file_exists($path . $file . '.php')) {
            //$this->_config[$key] = include_once $path . $file . '.php';

            $values = include_once $path . $file . '.php';
            $this->_config->add($this->_arrayDot([
                        $key => $values
            ]));
        }
    }

    public function get($key) {
        return $this->_config->get($key);
    }

    protected function _arrayDot($array, $prepend = '') {
        $results = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, $this->_arrayDot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }

}
