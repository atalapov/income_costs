<?php
/*
   :: REST API
   :: Free As in Beer 
   :: https://github.com/j0nix/j0nix-rest-api

   Requirements:
   1.	Enable mod_rewrite
   2.	Set  "AllowOverride All" in host/vhost conf
   3.	Create a .htaccess file with below content and place in folder where this file exists
   4.	j0nix-rest-api/rest.class.php

   RewriteEngine On

   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteCond %{REQUEST_FILENAME} !-s
   RewriteRule ^(.*)$ api.php?rquest=$1 [QSA,NC,L]

   RewriteCond %{REQUEST_FILENAME} -d
   RewriteRule ^(.*)$ api.php [QSA,NC,L]

   RewriteCond %{REQUEST_FILENAME} -s
   RewriteRule ^(.*)$ api.php [QSA,NC,L]	

 */
error_reporting(0);

require_once("rest.class.php"); // or use autoloader

class API extends REST {

	/*
	Your API KEY for simple security and have little overhead in 
	access validation. But some argue that api-key in url is very 
	weak security, if any at all. Is security important you should
	also include some authentication...
	*/

	private $apiKey = array(
			"apiKey" => array(
				"EXAMPLE" //Functions allowed by this key
			),
	); 

	public function __construct(){
		parent::__construct();
	}

	public function processRequest(){
		if(!empty($this->_request['rquest'])) {
			$param = $this->_request;
			$rquest = $this->_request["rquest"];
			$rquest = explode("/", $rquest); 
			$param['_param']    = $param;
			$param['_response'] = $this;
			// do_action(array($rquest[0],$rquest[2]),);
			// var_dump(array($rquest[0],$rquest[1]));
			$data = array(
				'data' => $param
			);			
			do_action("{$rquest[0]}_{$rquest[1]}", $param);
		}
	}

	private function EXAMPLE($param) {
		//split parameter into an array
		$param = explode("/", $param); 
		switch($this->_method) {
			case "POST":
				break;
			case "PUT":
				break;
			case "DELETE":
			case "GET":
			default:
				$this->response($this->json(
						array( "METADATA" => 
							array(	"REQUEST" => $this->_method, 
								"PARAM" => implode("/",$param), 
								"FUNCTION" => __FUNCTION__), 
							"DATA" => 
							array(	"PARAM" => $param)
						     )),200);
				break;
		}
	}

}
$api = new API; 
$api->processRequest();
?>
