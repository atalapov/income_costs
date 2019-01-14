<?php
/**
 * Copyright (c) 2019. Dev by Marcus {code}
 */

/**
 * Class Core run system
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!defined('SHSYS')){
    die('No access');
}
class Core{
    protected $libpath = "";
    protected $corepath = "";
    protected $Hook = null;
    protected $config_filename = null;
    public function __construct()
    {
        $this->corepath = dirname(__FILE__).DIRECTORY_SEPARATOR;
        $this->load("core".DIRECTORY_SEPARATOR.'PHP-Hooks'.DIRECTORY_SEPARATOR.'php-hooks.php',null,'core');
        $this->add_core_functions();
        add_filter('check_config_file',array($this,'check_config_file'),10,1);
        add_action('install_databace_config_file',array($this,'install_databace_config_file'),10);
        add_action('run_shsystem',array($this,'run_api'),10);
    }

    public function check_config_file($file_exist){
        $config_filename = apply_filters('get_database_config_file_name','config.php');
        if(file_exists(SHPATH.$config_filename)){
            $file_exist = SHPATH.$config_filename;
        }
        return $file_exist;
    }
    public function run(){
        $conf_exict = apply_filters('check_config_file',false);
        if(!$conf_exict){
            do_action('install_databace_config_file');
        }else{
            require_once $conf_exict;
            $config = apply_filters( 'get_database_conf', array() );
            $this->load("core".DIRECTORY_SEPARATOR.'PHP-MySQLi-Database-Class'.DIRECTORY_SEPARATOR.'MysqliDb.php',null,'core');
            $default = array(
                    'host' => 'localhost',
                    'username' => '', 
                    'password' => '',
                    'db'=> '',
                    'prefix'  => '',
                    'charset' => 'utf8'
                );
            $options = array_merge($default,$config);
            $db = new MysqliDb($options);
            if($db){
                $GLOBALS['db'] = $db;
                do_action('run_shsystem');
            }
        }
    }
    public function install_databace_config_file(){

    }
    public function load($filepath = "",$vars = array(), $type="")
    {
        switch ($type){
            case "core":
                require_once $this->corepath.$filepath;
            break;
        }
    }

    public function add_core_functions()
    {
        $folder = $this->corepath.'core'.DIRECTORY_SEPARATOR.'functions';
        $files = scandir($folder);
        foreach ($files as $file) {
            $file = ($file != '.' && $file !='..' && $file != 'index.php' && strpos($file,'.php') > 0) ? $file : '';
            if(!empty($file) && is_file($folder.DIRECTORY_SEPARATOR.$file)){
                require_once $folder.DIRECTORY_SEPARATOR.$file;
            }
        }
    }
    public function run_api()
    {
        $this->load("core".DIRECTORY_SEPARATOR.'j0nix-rest-api'.DIRECTORY_SEPARATOR.'api.php',null,'core');
    }
}

$core = new Core();
$GLOBALS['core'] = $core;