<?php

namespace App\Models;

class Contragent extends \App\Models\Core\Default_Object_Model {

    const TYPE_CUSTOMER = 'customer';
    const TYPE_SUPPLIER = 'supplier';

    protected $_oldRow = null;
    protected $_protected = ['user_id'];

    public function getPrimaryKey() {
        return 'id';
    }

    public function getTable(): string {
        return 'contragents';
    }

    public function getRules(): array {
        return [
            [
                'field' => 'name',
                'label' => 'name',
                'rules' => 'required'
            ],
            [
                'field' => 'bulstat',
                'label' => 'bulstat',
                'rules' => 'required'
            ],
            [
                'field' => 'city',
                'label' => 'city',
                'rules' => 'required'
            ],
            [
                'field' => 'type',
                'label' => 'type',
                'rules' => 'required|in_list[' . self::TYPE_CUSTOMER . ',' . self::TYPE_SUPPLIER . ']'
            ],
        ];
    }

    protected function afterFind() {
        $this->_oldRow = $this->_data;
    }

    protected function beforeValidation() {
        if ($this->_oldRow['bulstat'] != $this->_data['bulstat']) {
            $exists = $this->db
                    ->where('bulstat', $this->_data['bulstat'])
                    ->get($this->getTable())
                    ->row();

            if (isset($exists->id)) {
                $this->_errors['bulstat_exists'] = 'The bulstat must be unique.';
            }
        }
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
