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
      $args = array();
      // Do 'all' actions first
      if ( isset($hooks->filters['all']) ) {
        $hooks->current_filter[] = $tag;
        $args = func_get_args();
        $hooks->_call_all_hook($args);
      }

      if ( !isset($hooks->filters[$tag]) ) {
        if ( isset($hooks->filters['all']) )
          array_pop($hooks->current_filter);
        return $value;
      }

      if ( !isset($hooks->filters['all']) )
        $hooks->current_filter[] = $tag;

      // Sort
      if ( !isset( $hooks->merged_filters[ $tag ] ) ) {
        ksort($hooks->filters[$tag]);
        $hooks->merged_filters[ $tag ] = true;
      }

      reset( $hooks->filters[ $tag ] );

      if ( empty($args) )
        $args = func_get_args();

      do {
        foreach( (array) current($hooks->filters[$tag]) as $the_ )
          if ( !is_null($the_['function']) ){
            $args[1] = $value;
            $value = call_user_func_array($the_['function'], array_slice($args, 1, (int) $the_['accepted_args']));
          }

      } while ( next($hooks->filters[$tag]) !== false );

      array_pop( $hooks->current_filter );

      return $value;
}