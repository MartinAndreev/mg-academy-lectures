<?php

namespace Application\Controllers;

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Index_Controller extends \System\Core\Controller {

    function indexAction() {
        return $this->view->display('index.twig');
    }

}
