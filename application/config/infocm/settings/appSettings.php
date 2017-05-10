<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
$config['appSettings']= array(
	// database mapping information
	'db_mappings' => array(
		'servers' => array(
				'pp_infoc'=> 'mysql:host=master.infoc.idc.pplive.cn;dbname=pp_infoc|dev|dev!@#',
		),
		
		'shardings' => array(
			'infocm.data' => array(
					'server' => 'pp_infoc',
					'table' =>  'data'
					/****
					'table' =>  array('data_001','data_002','data_003','data_004','data_005','data_006','data_007','data_008','data_009','data_010',
									  'data_011','data_012','data_013','data_014','data_015','data_016','data_017','data_018','data_019','data_020',
									  'data_021','data_022','data_023','data_024','data_025','data_026','data_027','data_028','data_029','data_030',
									  'data_031','data_032'),****/
					)
		),
	),
	
	// redis mapping information
	'redis_mappings' => array(
		'infoc.nn' => 'redis1.infoc.idc.pplive.cn|6379|0',
	),

	// file logger configuration
	'log_filename' => '/tmp/{{$appname}}/{{$severity}}_{{date("Y-m-d")}}.log',
	'log_message' => '{{date("Y-m-d H:i:s")}} {{$_SERVER["REQUEST_URI"]}} "{{$message}}"', //{{$_SERVER["REQUEST_URI"]}}

	//my config
	'app_config'=> array(
		'controller_namespace' 	=> '\\infocm\\controller\\',
		'url_mapping'			=> array(),
		'handle_error'			=> array('error', 'error'),
		'deny_data_types' 		=> array( //这些数据类型不允许在数据模块里面操作(如：添加、编辑、修改数据)，同时不允许被定义、删除等
									'infoc_module', 'infoc_manager', 'infoc_apps'
		),
		'base_fields' 			=> array(//实际字段列表
									'n1'	=> array('name' => '数字类型1', 	'func_format' => 'intval'),
									'n2'	=> array('name' => '数字类型2', 	'func_format' => 'intval'),
									'fn1'	=> array('name' => '浮点类型1', 	'func_format' => 'floatval'),
									'fn2'	=> array('name' => '浮点类型2', 	'func_format' => 'floatval'),
									's1'	=> array('name' => '字符串类型1', 	'func_format' => ''),
									's2'	=> array('name' => '字符串类型2', 	'func_format' => ''),
									's3'	=> array('name' => '字符串类型3', 	'func_format' => '')
		),
		'free_login' 			=> array(//不需要登陆的controller和action
									'welcome' => array('login', 'captcha','login2')
		),
		'cookie_security_key' 	=> '01kkzapzsoxka021', //COOKIE加密码key
		'cookie_key'			=> 'pp_infoc_auth', //本地Cookie Key
	),
);