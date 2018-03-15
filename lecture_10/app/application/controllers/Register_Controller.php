<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Register_Controller {

    function __construct() {
        
    }

    function indexAction() {

        if ($_POST) {
            $user = new User();
            $user->email = $_POST['email'];
            $user->name = $_POST['name'];
            $user->lastname = $_POST['lastname'];
            $user->setPassword($_POST['password']);
            $user->save();
        }

        Registry::instance()->getView()->load_template('login');
    }

}
