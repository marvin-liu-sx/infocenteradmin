<?php
require_once APPPATH . 'libraries/filelogger.php';
class LogFc
{
	/**
	 * 获取Logger对象
	 * @param $appname 日至记录域, 一般情况下, 为应用名称
	 * @example
	 * 		\oap\common\LogFc::get()->info('message');
	 * 		\oap\common\LogFc::get()->warn('message');
	 * 		\oap\common\LogFc::get()->fatal('message');
	 * 		\oap\common\LogFc::get()->error('message');
	 * 		\oap\common\LogFc::get()->debug('message');
	 */
	public static function get($appname='oap')
	{
		static $s_logger_array = array();
		if( isset($s_logger_array[$appname]) )
		{
			return $s_logger_array[$appname];
		}

		$config = ConfigFc::get($appname);
		$filename = $config->get('log_filename', '/tmp/{{$appname}}/{{$severity}}_{{date("Y-m-d")}}.log');
		$message = $config->get('log_message', '{{date("Y-m-d H:i:s")}} "{{$message}}"');
		$logger = new FileLogger($appname, $filename, $message);
		$s_logger_array[$appname] = $logger;
		return $logger;
	}
}
