<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * This class loads all system hooks
 *
 * @author Martin Andreev <marto@www-you.com>
 * @version 1.0
 * @since 2.0
 * @package com.www-you.wcms.system.core
 * @copyright (c) 2016, Martin Andreev
 */
class Hooks_Loading
{

    /**
     * Stors the directories to map
     * @var type 
     */
    protected $_directories = [
        APPPATH . 'hooks/'           => ['single', 'global'],
        APPPATH . 'modules/'         => ['single', 'global'],
    ];

    function init()
    {
        // Start the mapping proccess
        foreach ($this->_directories as $directory => $loadType) {
            $this->_map($directory, $loadType);
        }

        do_action('init');
    }

    protected function _map($directory = '', $loadType = [])
    {
        if (!is_dir($directory)) {
            return FALSE;
        }

        if (!is_array($loadType) || count($loadType) == 0) {
            $loadType = ['single', 'global'];
        }

        $files   = dir($directory);
        $loading = [];

        while (($file = $files->read()) !== FALSE) {

            if ($file == '.' || $file == '..') {
                continue;
            }

            $path = $directory . $file;

            // Skip other related to the module directories
            if (preg_match('/modules\/[a-z_]+\/$/ui', $path)) {
                $path .= 'hooks/';
            }

            if (is_dir($path)) {
                $this->_map($path . '/', $loadType);
            } else {
                $indentify = $this->_indentify($file);

                if (!$indentify || !in_array($indentify, $loadType)) {
                    continue;
                }

                $loading[] = $path;
                //require_once $path;
            }
        }

        // Load the global first
        usort($loading, function($a, $b)
        {
            $fistIsGlobal   = strpos($a, 'global');
            $secondIsGlobal = strpos($b, 'global');

            if ($fistIsGlobal == $secondIsGlobal) {
                return 0;
            }

            return ($a > $b) ? -1 : 1;
        });

        foreach ($loading as $load) {
            require_once $load;
        }
    }

    protected function _indentify($file)
    {
        $regexes = ['/^[a-z_]+(_|\.)hooks.php$/ui', '/^[a-z_]+(_|\.)hooks(_|\.)global\.php$/ui'];

        $valid = false;

        foreach ($regexes as $regex) {

            if (preg_match($regex, $file)) {

                if (strpos($file, 'global') !== FALSE) {
                    $valid = 'global';
                } else {
                    $valid = 'single';
                }
            }
        }

        return $valid;
    }

}