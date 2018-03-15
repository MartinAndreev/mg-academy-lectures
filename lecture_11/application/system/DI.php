<?php

namespace System;

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

/**
 * The dependency injection class. Used to load
 * all system dependencies for the system
 * 
 * @author Martin Andreev <martin.andreev92@gmail.coom>
 * @version 1.0
 * @since 1.0
 * @package com.mgacademy.lectures.framework
 */
class DI {

    /**
     * Stores registry instance
     * @var DI 
     */
    protected static $_instance = null;

    /**
     * Stores the dependency injection
     * @var type 
     */
    protected $_di = [];

    /**
     *
     * @var Dispatcher
     */
    protected $_dispatcher = null;

    protected function __construct() {
        
    }

    /**
     * Returns the registry instance
     * @return DI
     */
    static function getDefault() {

        if (!self::$_instance instanceof DI) {
            self::$_instance = new DI();
        }

        return self::$_instance;
    }

    /**
     * Sets the di instance
     * @param type $key
     * @param type $instance
     */
    public function set($key, $instance, $shared = false) {
        if ($shared && $this->isClosure($instance)) {
            $this->_di[$key] = $instance();
        } else {
            $this->_di[$key] = $instance;
        }
    }

    /**
     * Returns the di if it exsists
     * @param type $key
     * @param type $insrance
     * @return type
     */
    public function get($key) {
        if (isset($this->_di[$key])) {
            $object = $this->_di[$key];

            if ($this->isClosure($object)) {
                return $object();
            } else {
                return $object;
            }
        }

        return FALSE;
    }

    public function __set($key, $instance) {
        $this->set($key, $instance);
    }

    public function __get($key) {
        return $this->get($key);
    }

    function isClosure($t) {
        return is_object($t) && ($t instanceof \Closure);
    }

}
