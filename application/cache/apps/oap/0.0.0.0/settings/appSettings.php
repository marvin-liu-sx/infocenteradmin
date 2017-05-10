<?php return array(
	// app_mappings svn default
	'app_mappings' => array(
		/*
		'cms' => array(
				'version' => '1.0.0.0',
				'package' => 'file:D:/PPLive/PHP/AplusCMS/web/oap/apps/cms',
			),
		*/
	),

	// server --> app dispatcher
	'server_mappings' => array(
		'infoc.pptv.com'	=> '\infoc\bootstrap::run();',  
		'infocm.synacast.com' => '\infocm\bootstrap::run();',
	),
	
	// console tasks ( will be excuted per minite )
	'console_mappings' => array(
		'php:\cms\process\App::run(true);',
		'php:\orderno\process\App::run();',
		'eval:\oap\task\AppUpdator::process();',
		'eval:\oap\task\AppDeployer::process();',
	),
	
	// database mapping information
	'db_mappings' => array(
		'servers' => array(
			'db' => 'mysql:host=mysql1.oap.idc.pplive.cn;dbname=pplive_ovp|dev|dev!@#',
		),
		
		'shardings' => array(
			'oap' => array(
					'server' => 'db',
					'table' => 'T000000' ),
			'oap.queue' => array(
					'server' => 'db',
					'table' => 'Q000000'),
		),
	),
	
	// redis mapping information
	'redis_mappings' => array(
		'oap.redis' => array('tw-redis.aplus.idc.pplive.cn|19000|0'),
        'oap.redis4' => array('tw-redis.aplus.idc.pplive.cn|19000|0'),
		'oap.dpredis' => array('redis.dataprovider.idc.pplive.cn|19500|0'),
		'oap' => 'tw-redis.aplus.idc.pplive.cn|19000|0',
	),
	
	// file logger configuration
	'log_filename' => '/home/pplive/logs/{{$appname}}/{{$severity}}_{{date("Y-m-d")}}.log',
	'log_message' => '{{date("Y-m-d H:i:s")}} "{{isset($_SERVER["REQUEST_URI"])?($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]):""}}" "{{$message}}" "{{$file}}" {{$line}}',
	'log_reportgrey' => array( 'error' => 0.1, 'fatal' => 0.1 ),
);