<?php

namespace App\Models\Core;

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
abstract class Collection extends \CI_Model
{

    /**
     * This is the main database table
     * @var type
     */
    protected $_table = '';

    /**
     * The primary field
     * @var string
     */
    protected $_primaryKey = '';

    /**
     * The default select
     * @var string
     */
    protected $_select = 'SQL_CALC_FOUND_ROWS *';

    /**
     * The group by statement
     * @var type
     */
    protected $_groupBy = '';

    /**
     * The order string
     * @var string
     */
    protected $_orderBy = '';

    /**
     * Stores the data array
     * @var \App\Libraries\Core\Data[]
     */
    protected $_data = array();

    /**
     * Stores all the database joins
     * @var type
     */
    protected $_joins = array();

    /**
     * Stores all the database where queries
     * @var type
     */
    protected $_where = array();

    /**
     * Stores the collection params, can be used for filters
     * @var type
     */
    protected $_params = array(
        'per_page' => 20,
        'page'     => 1,
    );

    /**
     * The build sql
     * @var type
     */
    protected $_sql = '';

    /**
     * The data row class
     * @var string
     */
    protected $_dataClass = '\App\Libraries\Core\Data';

    /**
     * How much rows to show on a page
     * @var type
     */
    protected $_perPage = 20;

    /**
     * The current page
     * @var int
     */
    protected $_page = 1;

    /**
     * The total rows count
     * @var int
     */
    protected $_totalRows = 0;

    /**
     * The returned rows
     * @var type
     */
    protected $_returnedRows = 0;

    /**
     * The current row
     * @var \App\Libraries\Core\Data
     */
    protected $_row = null;

    /**
     * Enables calling additional data filters for the row
     * @var type
     */
    protected $_dataFilterCallback = '';

    /**
     * Should the class handle search queries from the List_Table finders
     * automatically
     * @var type
     */
    protected $_handleFindersAutomatically = true;

    /**
     * The table from
     * @var type
     */
    protected $_from = '';

    /**
     * The current row counter
     * @var int
     */
    protected $_currentRow = 0;

    function __construct($params = array())
    {
        parent::__construct();

        foreach ($params as $key => $value) {
            $this->_params[$key] = $value;
        }

        if (isset($this->_params['per_page']))
            $this->_perPage = $this->_params['per_page'];

        if (isset($this->_params['page']))
            $this->_page = $this->_params['page'];

        $this->_from = $this->_table;

        /*if (!isset($this->_params['language']) || $this->_params['language'] == '')
            $this->_params['language'] = \App\Models\Languages\Language::getCurrentLanguage()->getLanguageCode(); */

        $this->init();
    }

    public function init()
    {
        $this->_setDefaultOrder();
        $this->_setOrder();

        if ($this->_handleFindersAutomatically) {
            $this->_handleSystemParams();
        }

        $this->_buildSql();
        $this->_prepareData();
    }

    abstract protected function _filterToSql();

    /**
     * Sets the default order
     */
    protected function _setDefaultOrder()
    {
        if ($this->_primaryKey != '' && $this->_orderBy == '')
            $this->_orderBy = ' ORDER BY ' . $this->_table . '.' . $this->_primaryKey . ' DESC';

        if (isset($this->_params['order'])) {
            $this->_params['order'] = (strtolower($this->_params['order']) == 'desc') ? 'desc' : 'asc';
        }
    }

    /**
     * Sets the current order
     * @abstract
     */
    protected abstract function _setOrder();

    /**
     * Returns the primary key field
     * @return type
     */
    function getPrimaryKey()
    {
        return $this->_primaryKey;
    }

    /**
     * Builds the sql query
     * @return void
     */
    protected function _buildSql()
    {
        $this->_filterToSql();

        if (count($this->_where) == 0) {
            $this->_where['initial'] = '1 = 1';
        }

        $limit = '';

        if ($this->_perPage != -1) {
            $limit = ' LIMIT ' . ($this->_page - 1) * $this->_perPage . ',' . $this->_perPage;
        }

        $this->_sql = 'SELECT ' . $this->_select . ' FROM ' . $this->_from . ' ' . implode(' ', $this->_joins) . ' WHERE ' . implode(' AND ', $this->_where) . ' ' . $this->_groupBy . ' ' . $this->_orderBy . $limit;
    }

    /**
     * Prepares the data.
     * Sets every data row as an object of the selected class
     *
     * @return void
     */
    protected function _prepareData()
    {
        $data = $this->db->query($this->_sql)->result();

        $this->_totalRows    = $this->db->query('SELECT FOUND_ROWS() AS total')->row()->total;
        $this->_returnedRows = count($data);


        foreach ($data as $row) {
            $this->_data[] = new $this->_dataClass($row);
        }
    }

    /**
     * Checks if we have more rows
     * @return boolena
     */
    public function haveRows()
    {
        return count($this->_data) > 0 && $this->_currentRow < $this->_returnedRows;
    }

    /**
     * Sets the current row
     * @return \App\Libraries\Core\Data
     */
    public function theRow()
    {
        $this->_row = $this->_data[$this->_currentRow];

        if ($this->_dataFilterCallback != '') {
            $this->_row = call_user_func_array($this->_dataFilterCallback, array($this->_row));
        }

        $this->_currentRow++;

        return $this->_row;
    }

    /**
     * Returns the total found rows
     * @return int
     */
    public function getTotalRows()
    {
        return $this->_totalRows;
    }

    /**
     * Sets the data filter callback
     * @param type $function
     */
    public function setDataFilterCallback($function)
    {
        $this->_dataFilterCallback = $function;
    }

    /**
     * Resets the loop
     * @return void
     */
    public function reset()
    {
        $this->_currentRow = 0;
    }

    /**
     * Generates the order
     * @return string
     */
    public function generateOrder()
    {

        if (isset($this->_params['order_by']) && $this->_params['order_by'] != '' && isset($this->_params['order']) && $this->_params['order'] != '' && isset($this->_params['filters']) && isset($this->_params['from_listing'])) {

            if (in_array($this->_params['order_by'], $this->_params['columns'])) {
                $field = $this->_params['order_by'];
                $field = (isset($this->_params['from_table']) && isset($this->_params['from_table'][$field])) ? $this->_params['from_table'][$field] . '.' . $this->db->escape_str($field) : $this->db->escape_str($field);

                $order = (strtolower($this->_params['order']) == 'asc') ? 'ASC' : 'DESC';

                $this->_orderBy = 'ORDER BY ' . $field . ' ' . $order;
            }
        } else {
            if (isset($this->_params['order_by']) && $this->_params['order_by'] != '') {
                $this->_orderBy = ' ORDER BY ' . $this->db->escape_str($this->_params['order_by']) . ' ' . ((strtolower($this->_params['order']) == 'asc') ? 'asc' : 'desc');
            }
        }

        return $this->_orderBy;
    }

    /**
     * Handles the search finder generated by the listing class.
     * If you wont write custom search queries just call this one
     * This method wont call itself
     */
    protected function _handleSystemParams()
    {
        if (isset($this->_params['filters']) && isset($this->_params['from_listing'])) {
            foreach ($this->_params['filters'] as $filter => $value) {
                if (isset($this->_params['finders'][$filter])) {
                    $finder = $this->_params['finders'][$filter];

                    if ($value == '') {
                        continue;
                    }

                    if ($finder['type'] == \App\Libraries\Core\List_Table::FINDER_TYPE_DATEPICKER) {
                        $finder['field'] = 'DATE(' . $finder['field'] . ')';
                        $value           = date('Y-m-d', strtotime($value));
                    }

                    if (isset($finder['from_table']) && $finder['from_table'] != '') {
                        $finder['field'] = $finder['from_table'] . '.' . $finder['field'];
                    }

                    switch ($finder['condition']) {
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_EQUAL:
                            $this->_where['finder_' . $filter] = $finder['field'] . ' = ' . $this->db->escape($value);
                            break;
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_LIKE:
                            $this->_where['finder_' . $filter] = $finder['field'] . ' LIKE "%' . $this->db->escape_like_str($value) . '%"';
                            break;
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_NOT_LIKE:
                            $this->_where['finder_' . $filter] = $finder['field'] . ' NOT LIKE "%' . $this->db->escape_like_str($value) . '%"';
                            break;
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_BIGGER:
                            $this->_where['finder_' . $filter] = $finder['field'] . ' > ' . $this->db->escape($value);
                            break;
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_BIGGER_OR_EQUAL:
                            $this->_where['finder_' . $filter] = $finder['field'] . ' >= ' . $this->db->escape($value);
                            break;
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_LESS:
                            $this->_where['finder_' . $filter] = $finder['field'] . ' < ' . $this->db->escape($value);
                            break;
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_LESS_OR_EQUAL:
                            $this->_where['finder_' . $filter] = $finder['field'] . ' <= ' . $this->db->escape($value);
                            break;
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_IN:

                            $in = array();

                            if (is_array($value)) {
                                foreach ($value as $val) {
                                    $in[] = $this->db->escape($value);
                                }
                            }

                            $this->_where['finder_' . $filter] = $finder['field'] . ' IN (' . implode(',', $in) . ')';
                            break;
                        case \App\Libraries\Core\List_Table::FINDER_CONDITION_NOT_IN:

                            $in = array();

                            if (is_array($value)) {
                                foreach ($value as $val) {
                                    $in[] = $this->db->escape($value);
                                }
                            }

                            $this->_where['finder_' . $filter] = $finder['field'] . ' NOT IN (' . implode(',', $in) . ')';
                            break;
                    }
                }
            }
        }
    }

}