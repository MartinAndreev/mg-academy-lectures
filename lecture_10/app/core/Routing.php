<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Routing {

    protected $_protocol = '';
    protected $_segments = [];

    function __construct($protocol = 'QUERY_STRING') {
        $this->_protocol = $protocol;
        $this->_loadSegments();
    }

    protected function _loadSegments() {
        $segments = (isset($_SERVER[$this->_protocol])) ? $_SERVER[$this->_protocol] : [];

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

    /**
     * Does a redirect
     * @param url $url
     */
    public function redirect($url) {
        header('Location: ' . $url, true, 302);
        die();
    }

}
