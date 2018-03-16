<?php

namespace System\Core;

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

/**
 * Used for the loader for locate needed classes for the system
 * 
 * @author Martin Andreev <martin.andreev92@gmail.coom>
 * @version 1.0
 * @since 1.0
 * @package com.mgacademy.lectures.framework
 */
abstract class Model {

    protected $_data = [];

    function __get($name) {
        return \System\DI::getDefault()->{$name};
    }

    protected abstract function _getTable();

    protected abstract function _getPrimaryKey();

    function find($id) {
        $this->_data = current($this->db->select($this->_getTable(), "*", [
                    $this->_getPrimaryKey() . '[=]' => $id
        ]));
    }

    function insert($data) {
        return $this->db->insert($this->_getTable(), $data);
    }

    function update($data, $id) {
        return $this->db->update($this->_getTable(), $data, [
                    $this->_getPrimaryKey() => $id
        ]);
    }

    function delete() {
        return $this->db->delete($this->_getTable(), [
                    "AND" => [
                        [
                            $this->_getPrimaryKey() => $id
                        ]
                    ]
        ]);
    }

    function set($name, $value) {
        $this->_data[$name] = $value;
    }

    function get($name) {
        return (isset($this->_data[$name])) ? $this->_data[$name] : false;
    }

    function beforeInsert() {
        
    }

    function beforeDelete() {
        
    }

    function beforeUpdate() {
        
    }

    function save() {
        if (isset($this->_data[$this->_getPrimaryKey()])) {
            $this->beforeUpdate();
            return $this->update($this->_data, $this->_data[$this->_getPrimaryKey()]);
        } else {
            $this->beforeInsert();
            return $this->insert($this->_data);
        }
    }

}
