<?php
/**
 * Created by PhpStorm.
 * User: Marcus
 * Date: 11.01.2019
 * Time: 18:36
 */
function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1){
    global $hooks;
    return $hooks->add_action($tag, $function_to_add, $priority, $accepted_args);
}
function has_action($tag, $function_to_check = false){
    global $hooks;
    return $hooks->has_action($tag, $function_to_check);
}
function remove_action( $tag, $function_to_remove, $priority = 10 ){
    global $hooks;
    return $hooks->remove_action( $tag, $function_to_remove, $priority);
}
function remove_all_actions($tag, $priority = false){
    global $hooks;
    return $hooks->remove_all_actions($tag, $priority);
}
function do_action($tag, $arg = ''){
    global $hooks;
    return $hooks->do_action($tag, $arg);
}
function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1){
    global $hooks;
    return $hooks->add_filter($tag, $function_to_add, $priority, $accepted_args);
}
function remove_filter( $tag, $function_to_remove, $priority = 10 ){
    global $hooks;
    return $hooks->remove_filter( $tag, $function_to_remove, $priority);
}
function remove_all_filters($tag, $priority = false){
    global $hooks;
    return $hooks->remove_all_filters($tag, $priority);
}
function has_filter($tag, $function_to_check = false){
    global $hooks;
    return $hooks->has_filter($tag, $function_to_check);
}
function apply_filters($tag, $value){
    global $hooks;
    return $hooks->apply_filters($tag, $value);
}