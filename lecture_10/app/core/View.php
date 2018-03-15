<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class View {

    function __construct() {
        
    }

    /**
     * Loads a template file
     * @param string $file
     */
    function load_template($file, $data = []) {
        $path = APP_PATH . 'views/' . $file . '.php';

        if (file_exists($path)) {
            include $path;
        }
    }

}
