<?php


require_once APPPATH . 'libraries/common.php';
class FileLogger
{
	private $filename;
	private $message;
	private $appname;
	private $mark_list;
	public function __construct($appname, $filename, $message)
	{
		$this->filename = $filename;
		$this->message = $message;
		$this->appname = $appname;
		$this->mark_list = array();
	}
	
	public function info($message, $file = '', $line = 0)
	{
		return $this->write('info', $message, $file, $line);
	}
	
	public function warn($message, $file = '', $line = 0)
	{
		return $this->write('warn', $message, $file, $line);
	}
	
	public function error($message, $file = '', $line = 0)
	{
		return $this->write('error', $message, $file, $line);
	}
	
	public function fatal($message, $file = '', $line = 0)
	{
		return $this->write('fatal', $message, $file, $line);
	}
	
	public function debug($message, $file = '', $line = 0)
	{
		return $this->write('debug', $message, $file, $line);
	}
	public function mark($name)
	{
		$cur_time = $this->microtime_float();
		$this->mark_list[$name][] = $cur_time;
		
	}
	private function mark_log(){
		if(count($this->mark_list) > 0){
			ksort($this->mark_list);
			$arr_mark_log = array();
			$str_mark_log = "";
			$mark_start_time = array();
			$mark_end_time = array();
			foreach($this->mark_list as $name=>$list){
				foreach ($list as $info){
					if(!isset($mark_start_time[$name])){					
						$mark_start_time[$name] = $info;
						continue;
					}
					if(empty($mark_start_time[$name])){					
						$mark_start_time[$name] = $info;
						continue;
					}
					if($mark_start_time[$name] > $info){
						$mark_start_time[$name] = $info;
						$mark_end_time[$name] = array();
						continue;
					}
					if(!isset($mark_end_time[$name])){
						$mark_end_time[$name] = $info;
					}
					if(empty($mark_end_time[$name])){
						$mark_end_time[$name] = $info;
					}
					$mark_interval_time = $mark_end_time[$name]-$mark_start_time[$name];
					$str_temp = "name : ".$name;
					$str_temp .= ",interval : ".$mark_interval_time;
					$arr_mark_log[] = $str_temp;
					$mark_start_time[$name] = array();
					$mark_end_time[$name] = array();
				}
			}
			foreach($arr_mark_log as $name => $str_mark_log){
				$this->write('mark', $str_mark_log);			
			}
		}
		$this->mark_list = array();
	}
	private function microtime_float()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}		
	
	private function write($severity, $message, $file = '', $line = 0)
	{
		$filename = $this->eva($this->filename, $severity, $message, $file, $line);
		$dir = dirname($filename);
		if( !is_dir($dir) )
		{
			if( !mkdir($dir, 0777, true) )
			{
				throw new \Exception('Failed to create log dir');
			}
		}		
		$message = $this->eva($this->message, $severity, $message, $file, $line);
		if (!file_exists($filename))
		{
			file_put_contents($filename, $message.PHP_EOL,FILE_APPEND);
			@chmod($filename, 0777);
		}
		else
		{
			file_put_contents($filename, $message.PHP_EOL,FILE_APPEND);
		}
		
		Common::reportLogInfo($this->appname, $severity, $message);
	}

	private function eva($format, $severity, $message, $file, $line)
	{
		$appname = $this->appname;

		// 找到下一个匹配到的表达式, 然后替换成具体内容
		for ($pos = 0; preg_match('/{{(.*?)}}/', $format, $match, PREG_OFFSET_CAPTURE, $pos); ) 
		{
			eval('$replace = '.$match[1][0].';');

			$format = substr_replace($format, $replace, $match[0][1], strlen($match[0][0]) );

			$pos = $match[0][1] + strlen($replace); // skip to end of replacement for next iteration
		}

		return $format; 
	}
	
	public function __destruct(){
		$this->mark_log();
	}
}