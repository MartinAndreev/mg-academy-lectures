<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * The hooks helper
 *
 * @author Martin Andreev <marto@www-you.com>
 * @version 1.0
 * @since 1.6
 * @package com.www-you.wcms.system.core
 * @copyright (c) 2015, Martin Andreev
 */
use App\Libraries\Core\Hooks as Hooks;

if (!function_exists('do_action')) {

    /**
     * do_action Execute functions hooked on a specific action hook.
     * @since 1.6
     * @param string $tag The name of the action to be executed.
     * @param mixed $arg,... Optional additional arguments which are passed on to the functions hooked to the action.
     * @return null Will return null if $tag does not exist in $filter array
     */
    function do_action($tag, $arg = '') {
        $args = func_get_args();
        call_user_func_array(array(Hooks::instance(), 'do_action'), $args);
    }

}

if (!function_exists('add_action')) {

    /**
     * add_action Hooks a function on to a specific action.
     * @access public
     * @since 0.1
     * @param string $tag The name of the action to which the $function_to_add is hooked.
     * @param callback $function_to_add The name of the function you wish to be called.
     * @param int $priority optional. Used to specify the order in which the functions associated with a particular action are executed (default: 10). Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.
     * @param int $accepted_args optional. The number of arguments the function accept (default 1).
     */
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        return Hooks::instance()->add_action($tag, $function_to_add, $priority, $accepted_args);
    }

}

if (!function_exists('has_action')) {

    /**
     * has_action Check if any action has been registered for a hook.
     * @access public
     * @since 0.1
     * @param string $tag The name of the action hook.
     * @param callback $function_to_check optional.
     * @return mixed If $function_to_check is omitted, returns boolean for whether the hook has anything registered.
     *   When checking a specific function, the priority of that hook is returned, or false if the function is not attached.
     *   When using the $function_to_check argument, this function may return a non-boolean value that evaluates to false
     *   (e.g.) 0, so use the === operator for testing the return value.
     */
    function has_action($tag, $function_to_check) {
        return Hooks::instance()->has_action($tag, $function_to_check);
    }

}

if (!function_exists('remove_action')) {

    /**
     * remove_action Removes a function from a specified action hook.
     * @access public
     * @since 0.1
     * @param string $tag The action hook to which the function to be removed is hooked.
     * @param callback $function_to_remove The name of the function which should be removed.
     * @param int $priority optional The priority of the function (default: 10).
     * @return boolean Whether the function is removed.
     */
    function remove_action($tag, $function_to_check, $priority) {
        return Hooks::instance()->remove_action($tag, $function_to_check, $priority);
    }

}

if (!function_exists('remove_all_actions')) {

    /**
     * remove_all_actions Remove all of the hooks from an action.
     * @access public
     * @since 0.1
     * @param string $tag The action to remove hooks from.
     * @param int $priority The priority number to remove them from.
     * @return bool True when finished.
     */
    function remove_all_actions($tag, $priority) {
        return Hooks::instance()->remove_all_actions($tag, $priority);
    }

}

if (!function_exists('apply_filters')) {

    /**
     * apply_filters Call the functions added to a filter hook.
     * @since 1.6
     * @param string $tag The name of the filter hook.
     * @param mixed $value The value on which the filters hooked to <tt>$tag</tt> are applied on.
     * @param mixed $var,... Additional variables passed to the functions hooked to <tt>$tag</tt>.
     * @return mixed The filtered value after all hooked functions are applied to it.
     */
    function apply_filters($tag, $value = '') {
        $args = func_get_args();
        return call_user_func_array(array(Hooks::instance(), 'apply_filters'), $args);
    }

}

if (!function_exists('add_filter')) {

    /**
     * add_filter Hooks a function or method to a specific filter action.
     * @access public
     * @since 0.1
     * @param string $tag The name of the filter to hook the $function_to_add to.
     * @param callback $function_to_add The name of the function to be called when the filter is applied.
     * @param int $priority optional. Used to specify the order in which the functions associated with a particular action are executed (default: 10). Lower numbers correspond with earlier execution, and functions with the same priority are executed in the order in which they were added to the action.
     * @param int $accepted_args optional. The number of arguments the function accept (default 1).
     * @return boolean true
     */
    function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        return Hooks::instance()->add_filter($tag, $function_to_add, $priority, $accepted_args);
    }

}

if (!function_exists('remove_filter')) {

    /**
     * remove_filter Removes a function from a specified filter hook.
     * @access public
     * @since 0.1
     * @param string $tag The filter hook to which the function to be removed is hooked.
     * @param callback $function_to_remove The name of the function which should be removed.
     * @param int $priority optional. The priority of the function (default: 10).
     * @param int $accepted_args optional. The number of arguments the function accepts (default: 1).
     * @return boolean Whether the function existed before it was removed.
     */
    function remove_filter($tag, $function_to_add, $priority) {
        return Hooks::instance()->remove_filter($tag, $function_to_add, $priority);
    }

}

if (!function_exists('remove_all_filters')) {

    /**
     * remove_all_filters Remove all of the hooks from a filter.
     * @access public
     * @since 0.1
     * @param string $tag The filter to remove hooks from.
     * @param int $priority The priority number to remove.
     * @return bool True when finished.
     */
    function remove_all_filters($tag, $priority) {
        return Hooks::instance()->remove_all_filters($tag, $priority);
    }

}