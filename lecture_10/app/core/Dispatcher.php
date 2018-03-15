<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Dispatcher {

    /**
     * Stores the routing
     * @var Routing
     */
    protected $_routing = null;
    protected $_controller = '';
    protected $_method = '';
    protected $_params = [];

    function __construct(Routing $routing) {
        $this->_routing = $routing;
    }

    public function dispatch() {
        $controller = 'Index_Controller';
        $method = 'indexAction';

        $this->_controller = $controller;
        $this->_method = $method;

        if ($this->_routing->getSegmentsCount() == 1) {
            $this->_controller = ucfirst($this->_routing->getSegment(0)) . '_Controller';
        } else if ($this->_routing->getSegmentsCount() > 1) {
            $this->_controller = ucfirst($this->_routing->getSegment(0)) . '_Controller';
            $this->_method = strtolower($this->_routing->getSegment(1)) . 'Action';
        }

        if ($this->_routing->getSegmentsCount() > 2) {
            $segments = $this->_routing->getSegments();
            $this->_params = array_splice($segments, 2);
        }

        $this->_loadController();
    }

    protected function _loadController() {
        if (class_exists($this->_controller)) {
            $object = new $this->_controller();

            $method = (method_exists($object, $this->_method)) ? $this->_method : 'indexAction';

            call_user_func_array([$object, $method], $this->_params);
        }
    }

}
