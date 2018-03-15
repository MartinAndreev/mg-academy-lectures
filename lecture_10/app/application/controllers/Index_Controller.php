<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Index_Controller {

    function __construct() {
        if (!User::isLogged()) {
            Registry::instance()->getRouting()->redirect('login');
        }
    }

    function indexAction() {
        Registry::instance()->getView()->load_template('index');
    }

}
