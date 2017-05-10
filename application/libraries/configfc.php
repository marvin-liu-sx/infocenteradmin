<?php


require_once APPPATH . 'libraries/sysutils.php';
require_once APPPATH . 'libraries/appsettings.php';
class ConfigFc
{
	/**
	 * 获取配置信息
	 * @param $appname 应用程序名
	 */
	public static function get($appname = 'oap')
	{
		// 如果已经创建,则直接返回
		static $s_config_array = array();
		if( isset($s_config_array[$appname]) )
		{
			return $s_config_array[$appname];
		}
		
		$appsetting_address = 	'http://config.aplusapi.pptv.com/web/'.$appname.
								'/appsettings-'.SysUtils::getIDCName().
								'?machine='.strtolower(php_uname('n')).
								'&key='.(time()-19283746);
		$appsettings = new AppSettings($appname, 'appSettings', $appsetting_address);
		$s_config_array[$appname] = $appsettings;
		return $appsettings; 
	}

	/**
	 * 从应用程序配置中, 获取复合设置对象
	 * @param $appname 应用程序名
	 * @param $key 复合设置项的名称
	 */
	public static function getComposite($appname, $key)
	{
		$value = self::get($appname)->get($key);
		return new AppSettings('oap', $key, $value);
	}
}