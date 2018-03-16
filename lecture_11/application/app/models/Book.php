<?php

namespace Application\Models;

class Book extends \System\Core\Model {

    protected function _getPrimaryKey() {
        return 'id';
    }

    protected function _getTable() {
        return 'books';
    }

    use \Application\Libraries\Updatable;
}