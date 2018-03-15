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
class Controller {

    function __get($name) {
        return \System\DI::getDefault()->{$name};
    }

}
