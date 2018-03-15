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
class Loader {

    /**
     * Stores the directories used to locate classes
     * @var type 
     */
    protected $_directories = [];

    /**
     * Stores the namespace directories to locate the loaders
     * @var type 
     */
    protected $_namespaces = [];

    function __construct() {
        
    }

    public function register() {
        spl_autoload_register([$this, 'loader']);
    }

    /**
     * Used for autoloading the classes
     * @param type $class
     */
    public function loader($class) {
        $found = false;
        if (count($this->_namespaces) > 0) {
            $found = $this->_searchNamespaces($class);
        }

        if (!$found && count($this->_directories) > 0) {
            $this->_searchDirectories($class);
        }
    }

    /**
     * Scans the directories in search for the needed file
     * @param type $class
     */
    protected function _searchDirectories($class) {
        $found = false;
        foreach ($this->_directories as $dir) {
            $path = $dir . $this->_fixClassName($this->_stripNamespace($class)) . '.php';
            if (file_exists($path)) {
                require_once $path;
                $found = true;
                break;
            }
        }

        return $found;
    }

    /**
     * Fixes the class so that it is the correct code standard
     * @param type $class
     */
    protected function _fixClassName($class) {
        return str_replace(' ', '_', ucwords(str_replace('_', ' ', $class)));
    }

    /**
     * Strips the namespace and returns only the class name
     * @param type $class
     * @return type
     */
    protected function _stripNamespace($class) {
        if (strpos($class, '\\') !== FALSE) {
            $array = explode('\\', $class);
            return array_pop($array);
        }

        return $class;
    }

    /**
     * Strips the class from the namespace
     * @param type $class
     * @return string
     */
    protected function _stripClassFromNamespace($class) {
        if (strpos($class, '\\') !== FALSE) {
            $array = explode('\\', $class);
            array_pop($array);

            return implode('\\', $array);
        } else {
            return '';
        }
    }

    /**
     * Searches the namespace for the class
     * @param type $class
     * @return boolean
     */
    protected function _searchNamespaces($class) {
        $namespace = $this->_stripClassFromNamespace($class);

        if (isset($this->_namespaces[$namespace])) {
            $path = $this->_namespaces[$namespace] . $this->_fixClassName($this->_stripNamespace($class)) . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
            return true;
        }

        return false;
    }

    public function registerDirectories($directories) {
        $this->_directories = array_merge($this->_directories, $directories);
    }

    public function registerNamespaces($namespaces) {
        $this->_namespaces = array_merge($this->_namespaces, $namespaces);
    }

    static function initLoader() {
        $loader = new Loader();

        $loader->registerNamespaces([
            'System\\Core' => SYSTEM_PATH . 'core/',
            'Application' => APP_PATH,
            'Application\\Controllers' => APP_PATH . 'controllers/',
            'Application\\Models' => APP_PATH . 'models/',
            'Application\\Libraires' => APP_PATH . 'libraries/',
            'Application\\Helpers' => APP_PATH . 'helpers/',
        ]);

        return $loader;
    }

}
