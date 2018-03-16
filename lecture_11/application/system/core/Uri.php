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
class Uri implements Interfaces\DIInjectable {

    protected $_protocol = '';
    protected $_segments = [];
    protected $_di = null;
    protected $_baseUrl = '';

    function __construct() {
        
    }

    function init() {
        $this->_protocol = ($this->_di->config->get('config.url_protocol')) ? $this->_di->config->get('config.url_protocol') : 'QUERY_STRING';
        $this->_loadSegments();
        $this->_setBaseUrl();
    }

    protected function _loadSegments() {
        $segments = ($this->_di->request->server->get($this->_protocol)) ? $this->_di->request->server->get($this->_protocol) : '';

        if ($segments !== '') {
            if (strpos($segments, '/') !== FALSE) {
                $this->_segments = explode('/', $segments);
            } else {
                $this->_segments = [$segments];
            }
        }
    }

    public function getSegments() {
        return $this->_segments;
    }

    public function getSegment($index) {
        return (isset($this->_segments[$index])) ? $this->_segments[$index] : false;
    }

    public function getSegmentsCount() {
        return count($this->_segments);
    }

    protected function _setBaseUrl() {
        if ($this->_di->config->get('condig.base_url') && $this->_di->config->get('condig.base_url') != '') {
            $this->_baseUrl = $this->_di->config->get('condig.base_url');
        } else {
            $this->_baseUrl = sprintf(
                    "%s://%s%s", isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI']);

            $this->_baseUrl = str_replace(implode('/', $this->_segments), '', $this->_baseUrl);
        }
    }

    /**
     * Does a redirect
     * @param url $url
     */
    public function redirect($url) {
        header('Location: ' . $url, true, 302);
        die();
    }

    public function getBaseUrl() {
        return $this->_baseUrl;
    }

    public function getUrl($segments) {
        if (is_array($segments)) {
            $segments = implode('/', $segments);
        }

        return $this->getBaseUrl() . $segments;
    }

    public function getDI() {
        return $this->_di;
    }

    public function setDI(\System\DI $di) {
        $this->_di = $di;
    }

}
