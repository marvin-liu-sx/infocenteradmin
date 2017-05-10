<?php


class infoapi
{
	const RETURN_FORMAT_XML 	= 1;//XML格式
	const RETURN_FORMAT_JSON	= 2;//JSON格式

	const API_GET_URL			= "http://infoc.pptv.com/data/get";//单条数据获取接口
	const API_PUT_URL 			= "http://infoc.pptv.com/data/persist";//存储接口(插入、修改、删除)
	const API_LIST_URL			= "http://infoc.pptv.com/data/list";//列表接口

	private $_err = array(
			'code'		=> 0,
			'message'	=> ''
	);
	private $_appId		= 'system';
	private $_appKey	= '0fdeae39b2a668c0f7968ad5b26e57d2';	

	public function __construct($appid = '', $appkey = '')
	{
		$appid && $this->_appId = $appid;
		$appkey && $this->_securityKey = $appkey;
	}

	/**
	 * 插入一条新数据
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param string $id 
	 * @param array $data 
	 * @return array
	 */
	public function insert($dataType, $dataSegment, $id, $data)
	{
		$post = array(
			'dt'		=> (string)$dataType,
			'ds'		=> (string)$dataSegment,
			'id'		=> (string)$id,
			'appid'		=> $this->_appId,
			'action'	=> 'save',
			'ext'		=> json_encode($data),
			'format'	=> self::RETURN_FORMAT_JSON,
		);

		//make sig
		$content = $post['dt'] . $post['ds'] . $post['id'] . $post['appid'] . $post['action'] . $post['ext'];
		$sign = $this->_makeSignature($content);
		$post['sign'] = $sign;

		//http query
		$response = $this->_httpPost(self::API_PUT_URL, $post);

		//parse response
		$ret = $this->_parseResponse($response);
		if($ret === false)
		{
			return false;
		}
		return true;
	}

	/**
	 * 更新数据
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param string $id 
	 * @param string $data 
	 * @return array
	 */
	public function update($dataType, $dataSegment, $id, $data)
	{
		$post = array(
			'dt'		=> (string)$dataType,
			'ds'		=> (string)$dataSegment,
			'id'		=> (string)$id,
			'appid'		=> $this->_appId,			
			'action'	=> 'edit',
			'ext'		=> json_encode($data),
			'format'	=> self::RETURN_FORMAT_JSON,
		);

		//make sig
		$content = $post['dt'] . $post['ds'] . $post['id'] . $post['appid'] . $post['action'] . $post['ext'];
		$sign = $this->_makeSignature($content);
		$post['sign'] = $sign;

		//http query
		$response = $this->_httpPost(self::API_PUT_URL, $post);

		//parse response
		$ret = $this->_parseResponse($response);
		if($ret === false)
		{
			return false;
		}
		return true;
	}

	/**
	 * 删除记录
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param string $id 
	 * @return array
	 */
	public function delete($dataType, $dataSegment, $id)
	{
		$post = array(
			'dt'		=> (string)$dataType,
			'ds'		=> (string)$dataSegment,
			'id'		=> (string)$id,
			'appid'		=> $this->_appId,			
			'action'	=> 'delete',
			'format'	=> self::RETURN_FORMAT_JSON,
		);

		//make sig
		$content = $post['dt'] . $post['ds'] . $post['id'] . $post['appid'] . $post['action'];
		$sign = $this->_makeSignature($content);
		$post['sign'] = $sign;		

		//http query
		$response = $this->_httpPost(self::API_PUT_URL, $post);

		//parse response
		$ret = $this->_parseResponse($response);
		if($ret === false)
		{
			return false;
		}
		return true;
	}

	/**
	 * 获取一条数据
	 * @param string $dataType 
	 * @param string $dataSegment
	 * @param string $id 
	 * @return array
	 */
	public function getRow($dataType, $dataSegment, $id)
	{
		$post = array(
			'dt'		=> (string)$dataType,
			'ds'		=> (string)$dataSegment,
			'id'		=> (string)$id,
			'appid'		=> $this->_appId,			
			'format'	=> self::RETURN_FORMAT_JSON
		);

		//make sig
		$content = $post['dt'] . $post['ds'] . $post['id'] . $post['appid'];
		$sign = $this->_makeSignature($content);
		$post['sign'] = $sign;

		//http query
		$response = $this->_httpPost(self::API_GET_URL, $post);

		//parse response
		$ret = $this->_parseResponse($response);
		if($ret === false)
		{
			return array();
		}
		return $ret['data'];
	}

	/**
	 * 获取列表
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param array $search_params
	 * @param array $attr_params
	 * @return array
	 */
	public function getList($dataType, $dataSegment = '', $search_params = array(), $attr_params = array())
	{
		$ids = '';
		$post = array(
			'dt'		=> $dataType,
			'appid'		=> $this->_appId,
			'range'		=> '0,20',
			'order'		=> 'desc',
			'orderby'	=> 'create_time',
			'ext'		=> json_encode($search_params),
			'format'	=> self::RETURN_FORMAT_JSON,
		);

		if($dataSegment){
			$post['ds'] = $dataSegment;
		}
		if(isset($search_params['ids'])){
			$ids = $search_params['ids'];
			$post['ids'] = $ids;
		}
		if(isset($attr_params['offset']) && isset($attr_params['count'])){
			$post['range'] = (int)$attr_params['offset'] .',' . $attr_params['count'];
		}
		if(isset($attr_params['order'])){
			$post['order'] = $attr_params['order'];
		}
		if(isset($attr_params['orderby'])){
			$post['orderby'] = $attr_params['orderby'];
		}

		//make sig
		$content = $post['dt'] . $dataSegment . $ids . $post['appid'] . $post['range'] . $post['order'] . $post['orderby'] . $post['ext'];
		$sign = $this->_makeSignature($content);
		$post['sign'] = $sign;		
		//http query
		$response = $this->_httpPost(self::API_LIST_URL, $post);

		//parse response
		$ret = $this->_parseResponse($response);
		if($ret === false)
		{
			return array('list' => array(), 'count' => 0);
		}	
		return array(
			'list'	=> $ret['list'], 
			'count'	=> $ret['cnt']
		);
	}

	/**
	 * 获取错误信息
	 * @return array
	 */
	public function getError()
	{
		return $this->_err;
	}

	/**
	 * http请求
	 * @param string $url 
	 * @param array $data 
	 * @return string
	 */
	private function _httpPost($url, $data)
	{
		$postdata = http_build_query((array)$data);
//		echo $url . '?' . $postdata . "<br>";die;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	/**
	 * 解析返回数据
	 * @param string $response 
	 * @return array
	 */
	private function _parseResponse($response)
	{
		$arr = json_decode($response, true);
		//echo $response;

		//response error
		if(!isset($arr['errcode'])){
			$this->_err['code']		= -1;
			$this->_err['message']	= $response;
			return false;
		}

		//has error
		if($arr['errcode']){
			$this->_err['code']		= $arr['errcode'];
			$this->_err['message']	= $arr['errmessage'];
			return false;
		}

		//return
		$this->_err['code']		= 0;
		$this->_err['message']	= '';
		return $arr;
	}

	/**
	 * 生成签名
	 * @param string $content 
	 * @return string
	 */
	private function _makeSignature($content)
	{
		$sig = md5($content . $this->_appKey);
		return $sig;
	}
}