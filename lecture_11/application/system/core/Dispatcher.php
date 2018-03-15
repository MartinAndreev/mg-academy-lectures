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
class Dispatcher {

    /**
     * Stores the routing
     * @var DI
     */
    protected $_di = null;
    protected $_controller = '';
    protected $_method = '';
    protected $_params = [];

    function __construct(\System\DI $di) {
        $this->_di = $di;
    }

    protected function _setController() {
        if ($this->_di->uri->getSegmentsCount() >= 1) {
            $this->_controller = str_replace(' ', '_', ucwords(str_replace(['-', '_'], ' ', $this->_di->uri->getSegment(0))));
        }

        $namespace = $this->_di->config->get('routing.controllers_namespace');
        if ($namespace && $namespace != '') {
            $this->_controller = $namespace . '\\' . $this->_controller;
        }
    }

    protected function _setMethod() {
        if ($this->_di->uri->getSegmentsCount() >= 2) {
            $method = str_replace(' ', '_', ucwords(str_replace(['-', '_'], ' ', $this->_di->uri->getSegment(1))));

            if (strpos($this->_method, '_') !== FALSE) {
                $parts = explode('_', $method);
                $first = array_shift($parts);

                $this->_method = strtolower($first) . implode('', $parts) . 'Action';
            } else {
                $this->_method = strtolower($method) . 'Action';
            }
        }
    }

    protected function _seDefault() {
        $this->_controller = ($this->_di->config->get('routing.default_controller')) ? $this->_di->config->get('routing.default_controller') : 'Index_Controller';
        $this->_method = 'indexAction';
    }

    protected function _setParams() {
        if ($this->_di->uri->getSegmentsCount() > 2) {
            $segments = $this->_di->uri->getSegments();
            $this->_params = array_splice($segments, 2);
        }
    }

    public function dispatch() {
        $this->_seDefault();
        $this->_setController();
        $this->_setMethod();
        $this->_setParams();

        $this->_loadController();
    }

    protected function _loadController() {
        if (class_exists($this->_controller)) {
            $object = new $this->_controller();

            $method = (method_exists($object, $this->_method)) ? $this->_method : 'indexAction';

            $responce = call_user_func_array([$object, $method], $this->_params);
            
            $this->_di->responce->prepare($this->_di->request);
            $this->_di->responce->setContent($responce);
            $this->_di->responce->send();
        }
    }

}
