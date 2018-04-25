<?php

namespace App\Libraries\Core;

use App\Libraries\Core\Hooks;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * The table listing class
 * Used for generating data tables for data listing
 *
 * @author Martin Andreev <marto@www-you.com>
 * @version 1.0
 * @since 1.6
 * @package com.www-you.cms.systen.core
 * @copyright (c) 2014, Martin Andreev
 */
class List_Table
{

    /**
     * A finder that generates and input field
     */
    const FINDER_TYPE_TEXT = 'text';

    /**
     * A finder that generates a select
     */
    const FINDER_TYPE_SELECT = 'select';

    /**
     * A finder that generates a multiple select
     */
    const FINDER_TYPE_MULTISELECT = 'multiselect';

    /**
     * A finder that generates a datepicker
     */
    const FINDER_TYPE_DATEPICKER = 'datepicker';

    /**
     * A finder that generates a datepicker
     */
    const FINDER_TYPE_RANGE_DATEPICKER = 'range_datepicker';

    /**
     * Sets the find condition to equal
     */
    const FINDER_CONDITION_EQUAL = 'equal';

    /**
     * Sets the finder condition to less
     */
    const FINDER_CONDITION_LESS = 'less';

    /**
     * Sets the finder condition to less
     */
    const FINDER_CONDITION_LESS_OR_EQUAL = 'less_or_equal';

    /**
     * Sets the finder condition to in
     */
    const FINDER_CONDITION_IN = 'in';

    /**
     * Sets the finder condition to like
     */
    const FINDER_CONDITION_LIKE = 'like';

    /**
     * Sets the finder condition to not like
     */
    const FINDER_CONDITION_NOT_LIKE = 'not_like';

    /**
     * Sets the finder condition to not in
     */
    const FINDER_CONDITION_NOT_IN = 'not_in';

    /**
     * Sets the finder condition to not in
     */
    const FINDER_CONDITION_BIGGER = 'bigged';

    /**
     * Sets the finder condition to not in
     */
    const FINDER_CONDITION_BIGGER_OR_EQUAL = 'bigged_or_equal';

    /**
     * Table row callbacks
     */
    const CALLBACK_TR = 'tr';

    /**
     * Table cell callbacks
     */
    const CALLBACK_TD = 'td';

    /**
     * The table string. Needed if you use the universal collection
     * @var string
     */
    protected $_table = '';

    /**
     * Set the rows per page options
     * @var type
     */
    public $perPageOptions = array(10, 20, 50, 100, 200);

    /**
     * The table primary key string. Needed if you use the universal collection
     * @var string
     */
    protected $_primaryKey = '';

    /**
     * The data collection
     * @var \Backend\Models\Core\Collection
     */
    protected $_collection = null;

    /**
     * If any field has . in its name, it will set a from table and pass
     * it to the universal collection
     * @var array
     */
    protected $_fromTable = array();

    /**
     * The columns to show
     * @var array
     */
    protected $_columns = array();

    /**
     * Indexes which table columns are html or plain text
     * @var array
     */
    protected $_htmlColumns = array();

    /**
     * Stores the columns labels
     * @var array
     */
    protected $_columnLabels = array();

    /**
     * Stores the sortable columns
     * @var type
     */
    protected $_sortable = array();

    /**
     * Stores the generated html
     * @var type
     */
    protected $_html = array();

    /**
     * Stores the finders data
     * @var type
     */
    protected $_finders = array();

    /**
     * Stores the ci instance
     * @var type
     */
    protected $_ci = null;

    /**
     * SHould it show the per page drop down
     * @var type
     */
    public $showPerPage = true;

    /**
     * The rendering templates
     * @var array
     */
    public $templates = array(
        'wrap_open'           => '<table class="table table-bordered table-striped wcms-listing-table" id="wcms-listing-table">',
        'wrap_close'          => '</table>',
        'per_page_wrap_open'  => '<div class="wcms-per-page-wrap">',
        'per_page_wrap_close' => '</div>',
        'per_page_template'   => '<label class="wcms-per-page-label" for="wcms-per-page">%1$s</label>%2$s',
        'pagination'          => array(
            'full_tag_open'   => '<div class="wcms-pagination clearfix"><ul class="pagination pagination-sm">',
            'full_tag_close'  => '</ul></div>',
            'first_tag_open'  => '<li>',
            'first_tag_close' => '</li>',
            'last_tag_open'   => '<li>',
            'last_tag_close'  => '</li>',
            'prev_tag_open'   => '<li>',
            'prev_tag_close'  => '</li>',
            'next_tag_open'   => '<li>',
            'next_tag_close'  => '</li>',
            'last_tag_open'   => '<li>',
            'last_tag_close'  => '</li>',
            'cur_tag_open'    => '<li><a href="javascript:void(0)" class="current">',
            'cur_tag_close'   => '</a></li>',
            'num_tag_open'    => '<li>',
            'num_tag_close'   => '</li>',
        )
    );

    /**
     * The collection params
     * @var type
     */
    protected $_collectionParams = array();

    /**
     * Stores the data filter callback
     * @var type
     */
    protected $_dataFilter = '';

    /**
     * The per page settings
     * @var type
     */
    protected $_perPage = 10;

    /**
     * The per page settings
     * @var type
     */
    protected $_curPage = 1;

    /**
     * Stores the generated pagination
     * @var type
     */
    protected $_pagination = '';

    /**
     * Stores the table actions
     * @var array
     */
    protected $_actions = array();

    /**
     * Stores the bulk actions
     * @var type
     */
    protected $_bulkActions = array();
    protected $_callbacks   = array(
        'tr' => array(),
        'td' => array(),
    );

    /**
     * Is the table ajax
     * @var type
     */
    public $ajax = false;

    /**
     * Sets the table and primary key. You need to set them if you dont use
     * the universal collection
     *
     * @param string $table
     * @param string $primaryKeys
     */
    function __construct($table = '', $primaryKey = '')
    {
        if ($table != '') {
            $this->setTable($table);
        }

        if ($primaryKey != '') {
            $this->setPrimaryKey($primaryKey);
        }

        $this->_ci = & get_instance();

        $this->_perPage = current($this->perPageOptions);
    }

    /**
     * Parses the field name to search for a table name
     * @param string $field
     * @return string
     */
    protected function _parseField($field)
    {
        if (strpos($field, '.') !== FALSE) {
            $field = explode('.', $field);

            $this->_fromTable[$field[1]] = $field[0]; // We found a specific table
            $field                       = $field[1];
        }

        return $field;
    }

    /**
     * Sets the table name. Only string allowed
     * @param string $table
     * @return void
     */
    public function setTable($table)
    {
        if (is_string($table) && $table != '') {
            $this->_table = $table;
        }
    }

    /**
     * Sets the primary key. Only string allowed
     * @param string $key
     * @return void
     */
    public function setPrimaryKey($key)
    {
        if (is_string($key) && $key != '') {
            $this->_primaryKey = $key;
        }
    }

    /**
     * Ads a column to the filter
     * @param string $column  The column name. Can be also html
     * @param boolean $sortable Should this column be sorted?
     * @param boolean $html Is it plain text or data field?
     * @return void
     */
    public function addColumn($column, $sortable = true, $html = false)
    {
        $index = count($this->_columns);

        if ($html == true) {
            $sortable = false;

            $this->_htmlColumns[$index] = $column;
        }

        if (is_array($column)) {
            $label  = current($column);
            $column = current(array_keys($column));
        } else {
            $label = $column;
        }

        $column = $this->_parseField($column);

        if ($sortable) {
            $this->_sortable[$index] = $column;
        }

        $this->_columns[$index]      = $column;
        $this->_columnLabels[$index] = _t($label);
    }

    /**
     * Adds a finder to the table
     * @param string $field
     * @param string $type
     * @param array $options
     * @param string $condition
     */
    public function addFinder($field, $type = self::FINDER_TYPE_TEXT, $options = array(), $condition = self::FINDER_CONDITION_LIKE)
    {
        $this->_finders[$field] = array(
            'type'       => $type,
            'field'      => $field,
            'options'    => $options,
            'condition'  => $condition,
            'from_table' => (isset($this->_fromTable[$field])) ? $this->_fromTable[$field] : ''
        );
    }

    /**
     * Sets the per page options and resets the array
     * @param type $options
     */
    public function setPerPageOptions($options)
    {
        $this->perPageOptions = $options;

        $this->_perPage = current($this->perPageOptions);
    }

    /**
     * Adds a new row action
     * @param string $url
     * @param string $label
     * @param string $icon
     */
    public function addAction($url, $label, $icon = '', $attributes = array())
    {
        $this->_actions[] = array(
            'url'        => $url,
            'label'      => $label,
            'icon'       => $icon,
            'attributes' => $attributes,
        );
    }

    /**
     * Renders the table header
     * @param type $html
     * @return string
     */
    protected function _renderHeader($html)
    {

        $html['table_header_open']    = '<thead>';
        $html['table_header_tr_open'] = '<tr>';

        if (count($this->_bulkActions) > 0) {
            $html['table_header_bulk_checkbox'] = '<th style="width:20px;"><input type="checkbox" class="select-all" /></th>';
        }

        foreach ($this->_columns as $index => $column) {
            $get   = $this->_ci->input->get();
            $class = array();

            if (in_array($column, $this->_sortable)) {
                $class[] = 'sorting';
            }

            if (isset($get['order_by']) && $get['order_by'] == $column) {
                $class[] = 'sorted';
                $class[] = 'sorting_' . strtolower($get['order']);
            }

            $get['order']    = (isset($get['order']) && $get['order'] == 'asc' && $get['order_by'] == $column) ? 'desc' : 'asc';
            $get['order_by'] = $column;


            $label                            = (in_array($column, $this->_sortable)) ? anchor(current_url() . '?' . http_build_query($get), $this->_columnLabels[$index], 'class="' . implode(' ', $class) . '"') : '<span>' . $this->_columnLabels[$index] . '</span>';
            $html['th_' . $column . '_open']  = '<th class="' . implode(' ', $class) . '">';
            $html['th_' . $column]            = $label;
            $html['th_' . $column . '_close'] = '</th>';
        }

        if (count($this->_actions) > 0) {
            $html['table_header_actions'] = '<th></th>';
        }

        $html['table_header_tr_close'] = '</tr>';

        if (count($this->_finders) > 0) {
            $html['table_header_finder_tr_open'] = '<tr>';

            if (count($this->_bulkActions) > 0) {
                $html['table_header_finder_bulk'] = '<th></th>';
            }

            foreach ($this->_columns as $field) {

                if (isset($this->_finders[$field])) {
                    $finder                                          = $this->_finders[$field];
                    $html['table_header_finder_' . $finder['field']] = '<th>' . $this->_renderFinder($finder) . '</th>';
                } else {
                    $html['table_header_finder_emply_' . $field] = '<th></th>';
                }
            }

            if (count($this->_actions) > 0) {
                $html['table_header_finder_actions'] = '<th></th>';
            }

            $html['table_header_finder_tr_close'] = '</tr>';
        }

        $html['table_header_close'] = '</thead>';

        return $html;
    }

    /**
     * Adds a table row callback
     * @param type $callback
     * @param type $type
     */
    public function addRowCallback($callback, $type = self::CALLBACK_TR)
    {
        $this->_callbacks[$type][] = $callback;
    }

    /**
     * Sets the collection params
     * @param array $params
     */
    public function setCollectionParams($params = array())
    {
        $this->_collectionParams = $params;
    }

    /**
     * Renders the finder
     * @param type $finder
     * @return type
     */
    protected function _renderFinder($finder)
    {
        $default = (isset($_GET['filters'][$finder['field']])) ? htmlspecialchars($_GET['filters'][$finder['field']]) : '';

        $html = '';

        $name = $finder['field'];
        $type = $finder['type'];

        if (isset($finder['options']) && count($finder['options']) > 0) {
            $finder['options'] = array('' => _t('Please select %s', _t($name))) + $finder['options'];
        }

        switch ($finder['type']) {
            case self::FINDER_TYPE_TEXT:
                $html = form_input('filters[' . $name . ']', $default, 'placeholder="' . _t('Search by %s', _t($name)) . '" class="form-control input-sm wcms-finder-type-text"');
                break;
            case self::FINDER_TYPE_SELECT:
                $html = form_dropdown('filters[' . $name . ']', $finder['options'], $default, 'class="form-control input-sm wcms-finder-type-text"');
                break;
            case self::FINDER_TYPE_DATEPICKER:
                $html = form_input('filters[' . $name . ']', $default, 'class="form-control input-sm wcms-finder-type-datepicker" placeholder="' . _t('Select a date') . '"');
                break;
        }

        return $html;
    }

    /**
     * Performs the bulk actions if found
     */
    protected function _performBulkActionIfFound()
    {
        if ($this->_ci->input->get('bulk_action') && $this->_ci->input->get('bulk')) {
            $action = $this->_ci->input->get('bulk_action');

            if (isset($this->_bulkActions[$action])) {
                call_user_func_array($this->_bulkActions[$action]['function'], $this->_bulkActions[$action]['params']);
            }
        }
    }

    public function init()
    {
        $this->_performBulkActionIfFound();

        $params = array(
            'finders' => $this->_finders
        );

        if ($this->_ci->input->get('per_page') && in_array($this->_ci->input->get('per_page'), $this->perPageOptions)) {
            $this->_perPage = (int) $this->_ci->input->get('per_page');
        }

        if ($this->_ci->input->get('page') && $this->_ci->input->get('page') > 1) {
            $this->_curPage = (int) $this->_ci->input->get('page');
        }

        $params = array_merge($params, $this->_collectionParams);

        $params['per_page'] = $this->_perPage;
        $params['page']     = $this->_curPage;

        $params['columns']    = $this->_columns;
        $params['from_table'] = $this->_fromTable;

        $params['from_listing'] = true;

        if ($this->_ci->input->get('order')) {
            $params['order'] = $this->_ci->input->get('order');
        }

        if ($this->_ci->input->get('order_by')) {
            $params['order_by'] = $this->_ci->input->get('order_by');
        }

        if ($this->_ci->input->get('filters')) {
            $params['filters'] = $this->_ci->input->get('filters');
        }

        //var_dump($this->_collection);

        if (is_string($this->_collection) && class_exists($this->_collection)) {
            $this->_collection = new $this->_collection($params);
        }

        if ($this->_collection == null) {
            $this->_collection = new \Backend\Models\Core\Universal_Collection($this->_table, $this->_primaryKey, $params);
        }

        if ($this->_dataFilter != '') {
            $this->_collection->setDataFilterCallback($this->_dataFilter);
        }

        $this->_generatePagination();

        if ($this->ajax) {
            add_action('footer', array($this, '_loadJs'));
            add_action('admin_footer', array($this, '_loadJs'));
        }
    }

    function _loadJs()
    {
        //echo '<script type="text/javascript" src="' . get_file_path('public/js/system/list_table.js') . '"></script>';
    }

    /**
     * Returns the rendered html. Calls <b>listing_table_render</b> filter in the render array
     * @return sting
     */
    public function render()
    {
        $this->_html['form_open'] = '<form action="' . current_url() . '" method="get" class="wcms-table-listing-form table-sorting" id="wcms-table-listing-form">';

        if ($this->_ci->input->get()) {
            foreach ($this->_ci->input->get() as $key => $value) {
                if ($key == 'filters') {
                    continue;
                }

                $this->_html['get_' . $key] = form_hidden($key, $value);
            }

            if ($this->_ci->input->get('filters')) {
                foreach ($this->_ci->input->get('filters') as $filter => $value) {
                    $this->_html['filter_' . $filter] = form_hidden('filter[' . $filter . ']', $value);
                }
            }
        }

        $perPage = array();

        foreach ($this->perPageOptions as $page) {
            $perPage[$page] = $page;
        }

        if ($this->showPerPage) {
            $this->_html['per_page_settings_open']  = $this->templates['per_page_wrap_open'];
            $this->_html['per_page_settings']       = sprintf($this->templates['per_page_template'], _t('Rows per page:'), form_dropdown('per_page', $perPage, $this->_ci->input->get('per_page'), 'class="form-control input-sm" id="wcms-per-page"'));
            $this->_html['per_page_settings_close'] = $this->templates['per_page_wrap_close'];
        }

        if (count($this->_finders) > 0) {
            $this->_html['filter_button'] = '<div class="wcms-filter_button"><button type="submit" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-filter"></i> ' . _t('Filter') . '</button></div>';
        }

        $this->_html['wrap_open'] = $this->templates['wrap_open'];

        $this->_html = $this->_renderHeader($this->_html);

        $row                  = 1;
        $this->_html['tbody'] = '<tbody>';

        if ($this->_collection->haveRows()) {
            while ($this->_collection->haveRows()) {
                $object = $this->_collection->theRow();

                $trAttributes = array();

                if (count($this->_callbacks[self::CALLBACK_TR]) > 0) {
                    foreach ($this->_callbacks[self::CALLBACK_TR] as $callback) {
                        $trAttributes = call_user_func($callback, $trAttributes, $object);
                    }
                }

                $trAttributes = $this->_parseAttributes($trAttributes);

                $this->_html['tbody_tr_' . $row . '_open'] = '<tr ' . $trAttributes . '>';

                if (count($this->_bulkActions) > 0) {
                    $this->_html['tbody_tr_' . $row . '_bulk'] = '<td><input type="checkbox" value="' . call_user_func(array($object, 'get' . $this->_primaryKey)) . '" name="bulk[]" class="bulk-action"/></td>';
                }

                foreach ($this->_columns as $key => $field) {

                    $tdAttributes = array();

                    if (count($this->_callbacks[self::CALLBACK_TD]) > 0) {
                        foreach ($this->_callbacks[self::CALLBACK_TD] as $callback) {
                            $tdAttributes = call_user_func($callback, $tdAttributes, $object, $field);
                        }
                    }

                    $tdAttributes = $this->_parseAttributes($tdAttributes);

                    $this->_html['tbody_td_' . $row . '_' . $field . '_open']  = '<td ' . $tdAttributes . '>';
                    $this->_html['tbody_td_' . $row . '_' . $field]            = call_user_func(array($object, 'get' . $field));
                    $this->_html['tbody_td_' . $row . '_' . $field . '_close'] = '</td>';
                }

                if (count($this->_actions) > 0) {
                    $this->_html['actions_' . $row] = $this->_renderActions($object, $row);
                }

                $this->_html['tbody_tr_' . $row . '_close'] = '</tr>';

                $row++;
            }
        }

        $this->_html['tbody_end'] = '</tbody>';

        $this->_html['wrap_close'] = $this->templates['wrap_close'];

        $this->_html['footer_open'] = '<div class="wcms-table-footer clearfix">';
        if (count($this->_bulkActions) > 0) {
            $this->_html['bulk_actions_open'] = '<div class="wcms-bulk-actions">';

            $actions = array(
                '' => _t('Apply a bulk action')
            );
            foreach ($this->_bulkActions as $action => $settings) {
                $actions[$action] = $settings['label'];
            }

            $this->_html['bulk_actions']       = form_dropdown('bulk_action', $actions, '', 'class="wcms-bulk-action form-control"') . '<button type="submit" class="btn btn-primary btn-xs">' . _t('Apply') . '</button>';
            $this->_html['bulk_actions_close'] = '</div>';
        }
        $this->_html['pagination'] = $this->getPagination();

        $pages = ceil($this->_collection->getTotalRows() / $this->_perPage);

        if ($pages == 0) {
            $this->_curPage = 0;
        }

        $this->_html['shown']        = '<div class="wcms-table-shown">' . _t('Shown %s from %s', $this->_curPage, $pages) . ' / ' . _t('Total rows: %s', $this->getCollection()->getTotalRows()) . '</div>';
        $this->_html['footer_close'] = '</div>';

        $this->_html['form_close'] = '</form>';

        $this->_html = Hooks::instance()->apply_filters('listing_table_render', $this->_html, $this->_ci->router->class, $this->_ci->router->method, $this->_ci->input->get());

        if ($this->_ci->input->is_ajax_request()) {
            unset($this->_html['form_open']);
            unset($this->_html['form_close']);

            ob_clean();
            echo implode(' ', $this->_html);
            die();
        }

        return implode(' ', $this->_html);
    }

    /**
     * Renders the row actions
     * @param type $object
     * @param type $row
     * @param type $action
     * @return type
     */
    protected function _renderActions($object, $row)
    {
        $html = array();

        $html['td_open']          = '<td class="wcms-table-actions">';
        $html['actions_dropdown'] = '<div class="dropdown"><button class="btn btn-default btn-xs dropdown-toggle action-button" '
                . ' type="button" id="actions-' . $row . '" data-toggle="dropdown" aria-haspopup="true" '
                . ' aria-expanded="true">' . _t('Actions') . ' <span class="caret"></span></button>';

        $html['drop_down_open'] = '<ul class="dropdown-menu dropdown-menu-right">';

        $current = 1;
        foreach ($this->_actions as $action) {

            if ($action['icon'] != '') {
                $action['label'] = $action['icon'] . ' ' . $action['label'];
            }

            preg_match_all('/{{[a-zA-Z0-9\_]+}}/ui', $action['url'], $matches);

            if (count($matches) > 0) {
                foreach ($matches as $matchParts) {

                    foreach ($matchParts as $match) {
                        $field = str_replace(array('{{', '}}'), '', $match);

                        $action['url'] = str_replace($match, call_user_func(array($object, 'get' . $field)), $action['url']);
                    }
                }
            }

            $html['action_' . $current] = '<li>' . anchor($action['url'], $action['label'], $action['attributes']) . '</li>';
            $current++;
        }

        $html['drop_down_close'] = '</ul>';

        $html['actions_dropdown_close'] = '</div>';
        $html['td_close']               = '</td>';

        $html = Hooks::instance()->apply_filters('listing_table_action_render', $html, $this->_ci->router->class, $this->_ci->router->method, $this->_ci->input->get());

        return implode('', $html);
    }

    protected function _parseAttributes(array $attributes)
    {
        $toString = array();
        foreach ($attributes as $attribute => $data) {
            $toString[] = $attribute . ' = "' . ((is_array($data)) ? implode(' ', $data) : $data) . '"';
        }

        return implode(' ', $toString);
    }

    /**
     * Generates the pagination
     */
    protected function _generatePagination()
    {
        $this->_ci->load->library('pagination');

        $config['base_url']             = current_url();
        $config['total_rows']           = $this->_collection->getTotalRows();
        $config['per_page']             = $this->_perPage;
        $config['page_query_string']    = TRUE;
        $config['reuse_query_string']   = TRUE;
        $config['use_page_numbers']     = TRUE;
        $config['query_string_segment'] = 'page';
        $config['full_tag_open']        = $this->templates['pagination']['full_tag_open'];
        $config['full_tag_close']       = $this->templates['pagination']['full_tag_close'];
        $config['first_link']           = _t('First');
        $config['last_link']            = _t('Last');
        $config['first_tag_open']       = $this->templates['pagination']['first_tag_open'];
        $config['first_tag_close']      = $this->templates['pagination']['first_tag_close'];
        $config['last_tag_open']        = $this->templates['pagination']['last_tag_open'];
        $config['last_tag_close']       = $this->templates['pagination']['last_tag_close'];
        $config['next_tag_open']        = $this->templates['pagination']['next_tag_open'];
        $config['next_tag_close']       = $this->templates['pagination']['next_tag_close'];
        $config['prev_tag_open']        = $this->templates['pagination']['prev_tag_open'];
        $config['prev_tag_close']       = $this->templates['pagination']['prev_tag_close'];
        $config['cur_tag_open']         = $this->templates['pagination']['cur_tag_open'];
        $config['cur_tag_close']        = $this->templates['pagination']['cur_tag_close'];
        $config['num_tag_open']         = $this->templates['pagination']['num_tag_open'];
        $config['num_tag_close']        = $this->templates['pagination']['num_tag_close'];

        $this->_ci->pagination->initialize($config);
        $this->_pagination = $this->_ci->pagination->create_links();
    }

    /**
     * Returns the generated pagination
     * @return string
     */
    public function getPagination()
    {
        return $this->_pagination;
    }

    /**
     * Returns the collection
     * @return \Backend\Models\Core\Collection
     */
    function getCollection()
    {
        return $this->_collection;
    }

    /**
     * Sets the collection. Can be either a string with the class name
     * or instance of the collection object
     * @param type $collection
     */
    function setCollection($collection)
    {
        $this->_collection = $collection;
    }

    /**
     * Sets the data filter
     * @param mixed $function Either a function name or an array with an object and method name
     * @return boolean
     * @throws \Exception
     */
    function setDataFilter($function)
    {
        if ((is_string($function) && function_exists($function)) || (is_array($function) && method_exists($function[0], $function[1])) || is_callable($function)) {
            $this->_dataFilter = $function;

            return true;
        } else {
            throw new \Exception('Cannot locate the function / method');
        }
    }

    /**
     * Sets a bulk action
     * @param string $action
     * @param string $label
     * @param mixed $function
     * @param array $params
     */
    function setBulkAction($action, $label, $function, $params = array())
    {
        $this->_bulkActions[$action] = array(
            'function' => $function,
            'label'    => $label,
            'params'   => $params,
        );
    }

}