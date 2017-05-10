<?php



class SysUtils
{
	/**
	 * 获取当前机房的名称(全局唯一).  公司的所有机器命名时有一定规范的, 比如:
	 * 	SGT2-live2-pms-212-77.idc.pplive.cn
	 * 	SHTB-EPG-DB-4-1.idc.pplive.cn
	 * 其中, 第一部门是机房名称. 所以可以根据机器名分析出机房的名称
	 */
	public static function getIDCName()
	{
		static $idc_name;
		if( !isset($idc_name) )
		{
			$idc = explode('-', php_uname('n'), 2);
			$idc_name = strtolower($idc[0]);			
		}
		return $idc_name;
	}
	
	/**
	 * 获取当前计算机名(全局唯一)
	 */
	public static function getComputerName()
	{
		list($name) = explode('.', php_uname('n'));
		$names = explode('-', $name);
		if( count($names) === 5 )
		{
			//符合公司的命名规范时, 返回简化名称: '[机房名]-[IP]'
			$ip = base_convert($names[3]*256+$names[4], 10, 36);
			return strtolower($names[0]).'-'.$ip;
		}
		else
		{
			return strtolower($name);
		}
	}
	
	public static function saveUrlToFile($url, $filename, $regex = false, $format = false)
	{
		// 1. 创建配置文件对应的目录
		$dir = dirname($filename);
		if( !is_dir($dir) )
		{
			if( !mkdir($dir, 0777, true) )
			{
				Common::reportError('Failed to create directory - '. $dir, __FILE__, __LINE__);
				return false;
			}
		}

		// 2. 通过URI读取配置文件内容
		$ctx = stream_context_create(array('http' => array('timeout' => 3)));
		$page_content = file_get_contents($url, null, $ctx);
		if( $page_content === false )
		{
			Common::reportWarning('Failed to read content, link : '. $url, __FILE__, __LINE__);
			return false;
		}

		if( $regex !== false )
		{
			if( preg_match($regex, $page_content, $matches) != 1 )
	        {
				Common::reportWarning('Invalid setting format, link : '. $url, __FILE__, __LINE__);
				return false;
	        }
	        $page_content = $matches[1];
		}

        // 3. 生成本地文件
		if( $format !== false )
		{
			$page_content = sprintf( $format, $page_content );
		}
		if( file_put_contents($filename, $page_content, LOCK_EX) == 0 )
		{
			Common::reportWarning('Failed to write file : '. $filename, __FILE__, __LINE__);
			return false;
		}

        return true;
	}
}