<?php

function _t() {
    $params = func_get_args();
    $params[0] = trim($params[0]);

    return call_user_func_array('sprintf', $params);
}
