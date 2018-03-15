<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Test_Controller {

    function indexAction() {
        echo "List of news";
    }
    
    function singleAction($newId) {
        echo "Single news";
        var_dump($newId);
    }

}
