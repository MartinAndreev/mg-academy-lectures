<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Login_Controller {

    function __construct() {
        
    }

    function indexAction() {
        Registry::instance()->getView()->load_template('login');
    }

}
