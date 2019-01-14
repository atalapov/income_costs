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
class income_costs
{
	
	public function __construct()
	{
		add_action(__CLASS__."_list",array($this,'list'),10,1);
	}
	public static function list($param = array())
	{
		global $db;
        $income_costs = $db->get('income_costs');
        
        $this->obj = $param['_response'];
		$obj = $param["_response"];
        $obj->response($obj->json($income_costs),200);
	}
}
// add_action( "income_costs_items", "income_costs_items", 10, 1 );
// function income_costs_items($param){
// 	var_dump($param);
// }
$income_costs = new income_costs();