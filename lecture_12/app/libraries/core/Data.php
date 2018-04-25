<?php

namespace App\Libraries\Core;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * The collection class. Used for data listing
 *
 * @author Martin Andreev <marto@www-you.com>
 * @version 1.0
 * @since 1.6
 * @package com.www-you.cms.system.core
 * @copyright (c) 2016, Martin Andreev
 */
class Data {

    /**
     * Stores the row data
     * @var stdClass 
     */
    protected $_row = null;

    /**
     * Sets the data in the class
     * @param type $row
     */
    function __construct($row) {
        $this->_row = $row;
    }
    
    function getRow() {
        return $this->_row;
    }

    /**
     * Used to fetch collected data when call getFieldName, when no such method
     * is found.
     * 
     * @param type $name
     * @param type $arguments
     * @return type
     */
    function __call($name, $arguments) {
        if (strpos($name, 'get') !== FALSE) {
            $key = substr($name, 3);

            if (isset($this->_row->{$key})) {
                return $this->_row->{$key};
            }
        } else if (strpos($name, 'set') !== FALSE) {
            $key = substr($name, 3);
            $this->_row->{$key} = current($arguments);
        } else if (strpos($name, 'has') !== FALSE) {
            $key = substr($name, 3);
            return isset($this->_row->{$key});
        }
    }

}