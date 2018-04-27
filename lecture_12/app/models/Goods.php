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
class Goods extends \App\Models\Core\Collection {

    protected $_table = 'goods';
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
        'search' => '',
        'user_id' => [],
        'is_active' => ''
    );

    protected function _filterToSql() {
        if ($this->_params['is_active'] != '') {
            $this->_where['is_active'] = 'is_active = ' . (int) $this->_params['is_active'];
        }

        if ($this->_params['search'] != '') {
            $this->_where['search'] = 'name LIKE "%' . $this->db->escape_like_str($this->_params['search']) . '%"';
        }

        if (is_array($this->_params['user_id']) && count($this->_params['user_id']) > 0) {
            $this->_where['user_id'] = 'user_id IN (' . implode(',', array_map([$this->db, 'escape'], $this->_params['user_id'])) . ')';
        }

        $this->_where = apply_filters('goods/query/where', $this->_where, $this->_params);
    }

    protected function _setOrder() {
        return $this->generateOrder();
    }

}
