<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class App_Loader extends CI_Loader {

    /**
     * CI Autoloader
     *
     * Loads component listed in the config/autoload.php file.
     *
     * @used-by	CI_Loader::initialize()
     * @return	void
     */
    protected function _ci_autoloader() {
        parent::_ci_autoloader();

        spl_autoload_register(array($this, 'namespace_autoload'));
    }

    /**
     * This method allows autoloading based on the namespace
     * @return void
     * @param string $class
     */
    public function namespace_autoload($class) {

        // The CI_Model class is not loaded by default
        if (!class_exists('CI_Model', FALSE)) {
            load_class('Model', 'core');
        }

        // The CI_Exceptions class is not loaded by default
        if (!class_exists('CI_Exceptions', FALSE)) {
            load_class('Exceptions', 'core');
        }

        $class = str_replace('\\', '/', $class);
        $class = explode('/', $class);
        $className = array_pop($class);
        $path = FCPATH . strtolower(implode('/', $class)) . '/' . $className . '.php';

        if (file_exists($path)) {
            require_once $path;
        }
    }

}
