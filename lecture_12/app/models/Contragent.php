<?php

namespace App\Models;

class Contragent extends \App\Models\Core\Default_Object_Model {

    const TYPE_CUSTOMER = 'customer';
    const TYPE_SUPPLIER = 'supplier';

    static protected $_cache = [];
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

    static function getCurrent() {
        $ci = & get_instance();
        $supplier = $ci->session->userdata('current_supplier');
        
        if ($supplier && unserialize($supplier) instanceof Contragent) {
            return unserialize($supplier);
        }

        $supplier = new Contragent();
        $exists = $supplier->find([
            'type' => self::TYPE_SUPPLIER,
            'user_id' => User::getLoggedUser()->id
        ]);

        if ($exists) {
            $ci->session->set_userdata([
                'current_supplier' => serialize($supplier)
            ]);

            return $supplier;
        }

        return false;
    }

    static function getSuppliers() {

        if (isset(self::$_cache['suppiers'])) {
            return self::$_cache['suppiers'];
        }

        self::$_cache['suppiers'] = [];

        $suppliers = new Contragents([
            'type' => [self::TYPE_SUPPLIER],
            'user_id' => [User::getLoggedUser()->id]
        ]);

        while ($suppliers->haveRows()) {
            $supplier = $suppliers->theRow();

            self::$_cache['suppiers'][$supplier->getid()] = $supplier->getname();
        }

        return self::$_cache['suppiers'];
    }

}
