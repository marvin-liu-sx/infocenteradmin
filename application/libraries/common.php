<?php


require_once APPPATH . 'libraries/logfc.php';
require_once APPPATH . 'libraries/configfc.php';
class Common
{
	public static function reportError($message, $file='', $line=0)
	{
		return LogFc::get()->error($message, $file, $line);
	}
	
	public static function reportWarning($message, $file='', $line=0)
	{
		return LogFc::get()->warn($message, $file, $line);
	}
	
	public static function reportLogInfo($appname, $severity, $message)
	{
		$reportGray = ConfigFc::get($appname)->get('log_reportgrey', array());
		if( isset($reportGray[$severity]) )
		{
			if( ( mt_rand(0, 9999) / 10000 ) < $reportGray[$severity] )
			{
				if( function_exists('curl_init') )
				{
					//读取主机名
					if( isset($_SERVER['HTTP_HOST']) )
					{
						$appname = $_SERVER['HTTP_HOST'];
						
						$ppi_code_seperator_index = strpos($appname, '.ppi.');
						if( $ppi_code_seperator_index !== false )
						{
							$appname = substr($appname, $ppi_code_seperator_index+5);
						}
						
						$scope_code_seperator_index = strpos($appname, '.fb.');
						if( $scope_code_seperator_index !== false )
						{
							$appname = substr($appname, $scope_code_seperator_index+4);
						}
					}
					
					//请求地址作为错误原因
					$url = 'NA';
					if( isset($_SERVER['REQUEST_URI']) )
					{
						$url = $_SERVER['REQUEST_URI'];
					}
					
					//准备错误数据
					$post_err_data = array(
						'ip'=>((isset($_SERVER["REMOTE_ADDR"]))?$_SERVER["REMOTE_ADDR"]:''),
						'service'=>$appname,
						'reason'=>$url,
						'time'=>date("Y-m-d H:i:s"),
						'desc'=>$message,
						'url'=>$appname.$url,
						'tid'=>'',
						'level'=>$severity);
					
					// 初始化CURL句柄 
				    $ch = curl_init();
					$url = 'http://errlog.pptv.com/log?'.time().rand(1000, 9999);
			    	curl_setopt($ch, CURLOPT_POST, 1);						//启用POST提交 
			    	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_err_data); 	//设置POST提交的字符串 
				    curl_setopt($ch, CURLOPT_URL, $url); 					//设置请求的URL 
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);				//设为TRUE把curl_exec()结果转化为字串，而不是直接输出 
				    curl_setopt($ch, CURLOPT_PORT, 80); 					//设置端口 
				    curl_setopt($ch, CURLOPT_TIMEOUT, 2); 					// 超时时间 
				    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
				        'Accept-Language: zh-cn', 
				        'Connection: Keep-Alive', 
				        'Cache-Control: no-cache' 
				    ));//设置HTTP头信息 
				    $document = curl_exec($ch); //执行预定义的CURL 
				    $info=curl_getinfo($ch); 	//得到返回信息的特性 
				}
			}
		}		
	}
}