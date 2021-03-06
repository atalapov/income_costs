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
        if(!empty($data)){
	        if(isset($data['date'])) {
	        	$data['date'] = date('Y-m-d H:i:s',strtotime($data['date']));
	        }else{
	        	$data['date'] = date('Y-m-d H:i:s');
	        }
	        $id = $db->insert('income_costs', $data);
	        $dataout = array(
	        	'id' => $id
	        );
	        $outdata = array(
	        	'dataout' => $dataout,
	        	'data'    => $data
	        );
	        $outdata = apply_filters('before_return_dataout', $outdata );
        	$obj->response( $obj->json($outdata['dataout']) ,200);
        }else{
        	$obj->response($obj->json(array('error'=>'Data can\'t be empty!' )),404);
        }
	}
}
// add_action( "income_costs_items", "income_costs_items", 10, 1 );
// function income_costs_items($param){
// 	var_dump($param);
// }
$income_costs = new income_costs();