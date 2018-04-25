<?php

namespace App\Models\Core;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

abstract class Default_Object_Model extends \CI_Model {

    /**
     * Stores the db columns data
     * @var type 
     */
    protected $_data = [];

    /**
     * Stores the protected db fields
     * @var type 
     */
    protected $_protected = [];

    /**
     * Stores the primary key values
     * @var type 
     */
    protected $_primaryKeyValues = [];

    /**
     * Stores additional data that is needed for the model
     * to handle
     * @var type 
     */
    protected $_additional = [];

    /**
     * Stores the validation errors
     * @var type 
     */
    protected $_errors = [];

    /**
     * Stores the database table fields
     * @var type 
     */
    protected static $_dbFields = [];

    /**
     * Checks if the data is a new record on exsisting one
     * @var type 
     */
    protected $_exists = false;

    public function __construct() {
        parent::__construct();

        $this->_describeTable();
    }

    /**
     * Returns the table name
     * @return string
     */
    abstract public function getTable(): string;

    /**
     * Returns the table primary key
     * @return string|array
     */
    abstract public function getPrimaryKey();

    /**
     * Returns the validation rules
     * @return array
     */
    public function getRules(): array {
        return [];
    }

    protected function _describeTable() {
        if (count(self::$_dbFields) == 0) {
            self::$_dbFields = $this->db->list_fields($this->getTable());
        }
    }

    /**
     * Sets the object model data
     * @param string $field
     * @param type $value
     */
    public function set(string $field, $value) {
        if (in_array($field, $this->_protected)) {
            return false;
        }

        if (!in_array($field, self::$_dbFields)) {
            $this->_additional[$field] = $value;
        } else {
            $primaryKey = (is_array($this->getPrimaryKey())) ? $this->getPrimaryKey() : [$this->getPrimaryKey()];

            if (in_array($field, $primaryKey)) {
                $this->_primaryKeyValues[$field] = $value;
                $this->_exists = true;
            }

            $this->_data[$field] = $value;
        }
    }

    /**
     * Returns the data from the object model
     * or if not found returns false
     * @param string $key
     * @return boolean
     */
    public function get(string $key) {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        } else if (isset($this->_additional[$key])) {
            return $this->_additional[$key];
        }

        return FALSE;
    }

    /**
     * Is the object valid
     * @return bool
     */
    public function isValid(): bool {
        return count($this->_errors) == 0;
    }

    /**
     * Returns the errors
     * @return array
     */
    public function getErrors(): array {
        return $this->_errors;
    }

    /**
     * Validates the passed data
     * @return bool
     */
    protected function _validate(): bool {
        $rules = $this->getRules();

        if (count($rules) == 0) {
            return true;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_data($this->_data);

        $valid = $this->form_validation->run();

        if (!$valid) {
            $this->_errors = array_merge($this->_errors, $this->form_validation->error_array());
        }

        return $valid;
    }

    public function save(): bool {

        $this->beforeValidation();

        if ($this->_exists) {
            $this->beforeValidationOnUpdate();
        } else {
            $this->beforeValidationOnCreate();
        }

        $this->_validate();

        if (!$this->isValid()) {
            return false;
        }
        $this->afterValidation();

        if ($this->_exists) {
            $this->afterValidationOnUpdate();
        } else {
            $this->afterValidationOnCreate();
        }

        $this->beforeSave();
        if (!$this->_exists) {
            $this->beforeCreate();
            $result = $this->_create();
            $this->afterCreate();
        } else {
            $this->beforeUpdate();
            $result = $this->_update();
            $this->afterUpdate();
        }
        $this->afterSave();

        return $result;
    }

    /**
     * Runs a create query
     * @return boolean
     */
    protected function _create(): bool {
        $result = $this->db
                ->insert($this->getTable(), $this->_data);

        if ($result) {
            $insertId = $this->db->insert_id();

            if ($insertId) {
                $this->_primaryKeyValues[$this->getPrimaryKey()] = $insertId;
                $this->_data[$this->getPrimaryKey()] = $insertId;
            }
        }

        return $result;
    }

    /**
     * Runs an update query
     * @return boolean
     */
    protected function _update(): bool {
        foreach ($this->_primaryKeyValues as $key) {
            unset($this->_data[$key]);
        }

        $result = $this->db
                ->where($this->_primaryKeyValues)
                ->update($this->getTable(), $this->_data);

        return $result;
    }

    public function delete(): bool {
        foreach ($this->_primaryKeyValues as $key) {
            unset($this->_data[$key]);
        }

        $result = $this->db
                ->where($this->_primaryKeyValues)
                ->delete($this->getTable());

        return $result;
    }

    /**
     * Runs before validation
     */
    protected function beforeValidation() {
        
    }

    /**
     * Runs before validation on create
     */
    protected function beforeValidationOnCreate() {
        
    }

    /**
     * Runs before validation on update
     */
    protected function beforeValidationOnUpdate() {
        
    }

    /**
     * Runs before validation
     */
    protected function afterValidation() {
        
    }

    /**
     * Runs before validation on create
     */
    protected function afterValidationOnCreate() {
        
    }

    /**
     * Runs before validation on update
     */
    protected function afterValidationOnUpdate() {
        
    }

    /**
     * Runs before the create method
     */
    protected function beforeCreate() {
        
    }

    /**
     * Runs before the update method
     */
    protected function beforeUpdate() {
        
    }

    /**
     * Runs before save method
     */
    protected function beforeSave() {
        
    }

    /**
     * Run after create
     */
    protected function afterCreate() {
        
    }

    /**
     * Run after update
     */
    protected function afterUpdate() {
        
    }

    /**
     * Runs after save
     */
    protected function afterSave() {
        
    }

    /**
     * Runs before delete
     */
    protected function beforeDelete() {
        
    }

    /**
     * Runs after delete
     */
    protected function afterDelete() {
        
    }

    /**
     * Redifned the set method
     * @param type $name
     * @param type $value
     */
    public function __set($name, $value) {
        $this->set($name, $value);
    }

    /**
     * Magic method for get
     * @param type $key
     * @return type
     */
    public function __get($key) {
        $found = $this->get($key);

        if ($found) {
            return $found;
        } else {
            return parent::__get($key);
        }
    }

    protected function afterFind() {
        
    }

    function find($condtions = []) {

        if (count($condtions) > 0) {
            $this->db->where($condtions);
        }

        $object = $this
                ->db
                ->get($this->getTable())
                ->row();

        if ($object) {
            foreach ($object as $key => $row) {
                $this->_data[$key] = $row;
            }

            $primeryKeys = (!is_array($this->getPrimaryKey())) ? [$this->getPrimaryKey()] : $this->getPrimaryKey();

            foreach ($primeryKeys as $key) {
                $this->_primaryKeyValues[$key] = $this->_data[$key];
            }

            $this->_exists = true;
            $this->afterFind();

            return true;
        } else {
            return false;
        }
    }

}
