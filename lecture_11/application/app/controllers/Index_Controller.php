<?php

namespace Application\Controllers;

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Index_Controller extends \System\Core\Controller {

    function indexAction() {
        $book = new \Application\Models\Book();
        $book->find('5');
        $book->set('book', 'Test');
        $book->set('isbn', 'asada');
        $book->set('author', 'asdadasd');
        $book->set('user_id', '1');
        $book->save();
    }

    function testAction() {
        
    }

}
