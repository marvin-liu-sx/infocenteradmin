<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

    protected  $redis = '';
	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}
	
	
	public function restful($err = 0, $msg = 'ok', $data = '', $expire = 0)
	{
	    $body = array();
	    
	    $body['errorCode'] = intval($err);          //应该是前端要求强制转换吧
	    
	    $body['msg'] = $msg ?: 'ok';
	    
	    $body['result'] = $data;               //响应的数据
	     $cb = $this->input->get('callback');
	    if (isset($cb)) {             //jsonp返回的标识？
	        
	        header('Content-Type:application/json; charset=utf-8');
	        
	        echo $cb.'(' . json_encode($body) . ');';die;               //jsonp格式
	        
	    } else {
	        
	       echo json_encode($body); die;
	    }
	    
	}

	public function getRedisObj(){
	    
	    $this->load->library('RedisTool');
	    
	    $this->config->load('redisConf',true);
	    
	    $redisConf = $this->config->item('redisConf');
	    
	    return $this->redistool->getIns($redisConf);
	    
	}	    
	
	function fillIsPayInfo($videos){
	    $redis = $this->getRedisObj();
	    foreach ($videos as $video){
	        $redis->hMGet('basic:'.$video->id, array('isPay'));
	    }
	    $rs = $redis->exec();
	    foreach ($videos as $key => $video) {
	        $video->isPay = $rs[$key]['isPay'];
	
	        $videos[$key] = $video;
	    }
	    return $videos;
	}
	
	
	public function getRid($id) {
	    $redis = $this->getRedisObj();
	    $redisInfo['rid'] = $redis->hmget('basic:'.$id,array('rid'));
	    $redisInfo['picURl'] = $redis->hmget('basic:'.$id,array('picURl'));
	    return $redisInfo;
	}
	
	public function errorPage($Error,$apiError){
	    header('Location: http://m.pptv.com/mapi_error/'.$Error.'/'.$apiError); die;
	}
	
}
