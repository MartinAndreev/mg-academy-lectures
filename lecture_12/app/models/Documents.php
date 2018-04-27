<?php

namespace App\Models;

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
class Documents extends \App\Models\Core\Collection {

    protected $_table = 'invoices';
    protected $_primaryKey = 'id';

    /**
     * Stores the collection params, can be used for filters
     * @var type
     */
    protected $_params = array(
        'per_page' => 20,
        'page' => 1,
        'order_by' => 'created_on',
        'order' => 'asc',
        'user_id' => [],
        'type' => [],
        'is_active' => ''
    );

    protected function _filterToSql() {
        if ($this->_params['is_active'] != '') {
            $this->_where['is_active'] = 'is_active = ' . (int) $this->_params['is_active'];
        }

        if (is_array($this->_params['type']) && count($this->_params['type']) > 0) {
            $this->_where['type'] = 'type IN (' . implode(',', array_map([$this->db, 'escape'], $this->_params['type'])) . ')';
        }

        if (is_array($this->_params['user_id']) && count($this->_params['user_id']) > 0) {
            $this->_where['user_id'] = 'user_id IN (' . implode(',', array_map([$this->db, 'escape'], $this->_params['user_id'])) . ')';
        }

        $this->_where = apply_filters('documents/query/where', $this->_where, $this->_params);
    }

    protected function _setOrder() {
        return $this->generateOrder();
    }

}
