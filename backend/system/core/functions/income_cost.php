<?php
/**
 * Created by PhpStorm.
 * User: Marcus
 * Date: 11.01.2019
 * Time: 18:50
 */
/**
 * 
 */
// class income_costs
// {
	
// 	public function __construct()
// 	{
// 	}
// 	public static function items($param = array())
// 	{
// 		var_dump($param);
// 	}
// }
add_action( "income_costs_items", "income_costs_items", 10, 1 );
function income_costs_items($param){
	var_dump($param);
}
// $income_costs = new income_costs();