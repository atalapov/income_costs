<?php
function get_db_conf($config){
	$config = array(
	    'username' => 'root',
	    'password' => '',
	    'db'       => 'income_costs',
	);
	return $config;
}
add_filter( 'get_database_conf', 'get_db_conf', 10, 1 );