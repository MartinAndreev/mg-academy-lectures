<?php

namespace Application\Libraries;

trait Updatable {

    public function beforeInsert() {
        $this->_data['created_on'] = strtotime('now');
        $this->_data['updated_on'] = strtotime('now');
    }

    public function beforeUpdate() {
        $this->_data['updated_on'] = strtotime('now');
    }

}
