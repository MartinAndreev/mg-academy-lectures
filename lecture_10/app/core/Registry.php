<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Registry {

    /**
     * Stores registry instance
     * @var Registry 
     */
    protected static $_instance = null;

    /**
     * Stores the config class
     * @var Config 
     */
    protected $_config = null;

    /**
     *
     * @var Database
     */
    protected $_db = null;

    /**
     *
     * @var Routing
     */
    protected $_router = null;

    /**
     *
     * @var View 
     */
    protected $_views = null;

    /**
     *
     * @var Dispatcher
     */
    protected $_dispatcher = null;

    protected function __construct() {
        $this->_load();
    }

    protected function _load() {
        $this->_config = new Config();
        $this->_db = new Database($this->_config->get('database'), $this->_config->get('username'), $this->_config->get('password'));

        $this->_router = new Routing();

        $this->_dispatcher = new Dispatcher($this->_router);
        $this->_views = new View();
    }

    /**
     * Returns config
     * @return Config
     */
    public function getConfig() {
        return $this->_config;
    }

    /**
     * Returns the registry instance
     * @return Registry
     */
    static function instance() {

        if (!self::$_instance instanceof Registry) {
            self::$_instance = new Registry();
        }

        return self::$_instance;
    }

    /**
     * 
     * @return Database
     */
    public function getDB() {
        return $this->_db;
    }

    /**
     * 
     * @return Routing
     */
    public function getRouting() {
        return $this->_router;
    }

    /**
     * 
     * @return Dispatcher
     */
    public function getDispatcher() {
        return $this->_dispatcher;
    }

    /**
     * 
     * @return View
     */
    public function getView() {
        return $this->_views;
    }

}
