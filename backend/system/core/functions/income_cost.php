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
		add_action(__CLASS__."_add",array($this,'add'),10,1);
	}
	public static function list($param = array())
	{
		global $db;
        $income_costs = $db->get('income_costs');
		$obj = $param["_response"];
        $obj->response($obj->json($income_costs),200);
	}
	public static function add($param = array())
	{
		global $db;
        $obj = $param["_response"];
        $data = $param["data"];
        $id = $db->insert ('income_costs', $data);
        $obj->response($obj->json(array('last_id'=>$id)),200);
	}
}
// add_action( "income_costs_items", "income_costs_items", 10, 1 );
// function income_costs_items($param){
// 	var_dump($param);
// }
$income_costs = new income_costs();