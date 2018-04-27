<?php

namespace App\Models;

class Good extends \App\Models\Core\Default_Object_Model {

    protected $_oldRow = null;
    protected $_protected = ['user_id'];

    public function getPrimaryKey() {
        return 'id';
    }

    public function getTable(): string {
        return 'goods';
    }

    public function getRules(): array {
        return [
            [
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required'
            ],
            [
                'field' => 'price',
                'label' => 'price',
                'rules' => 'required|numeric'
            ],
        ];
    }

    protected function beforeCreate() {
        $this->_data['created_on'] = strtotime('now');

        if (User::isLoogedIn()) {
            $this->_data['user_id'] = User::getLoggedUser()->id;
        }
    }

    protected function beforeSave() {
        $this->_data['updated_on'] = strtotime('now');
    }

}
