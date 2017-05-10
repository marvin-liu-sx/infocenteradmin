<?php

require_once APPPATH . 'libraries/infoapi.php';
class Datas extends CI_Model {
    private $_api = null;

	public function __construct()
	{
		$this->_api = new infoapi();
	}

	/**
	 * 添加数据
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param string $id 
	 * @param array $data 
	 * @return int
	 */
	public function addData($dataType, $dataSegment, $id, $data)
	{
		$res = $this->_api->insert($dataType, $dataSegment, $id, $data);
		return $res;
	}

	/**
	 * 编辑数据
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param string $id 
	 * @param array $data 
	 * @return int
	 */
	public function editData($dataType, $dataSegment, $id, $data)
	{
		$res = $this->_api->update($dataType, $dataSegment, $id, $data);
		return $res;
	}

	/**
	 * 数据列表
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param int $offset 偏移量
	 * @param int $count 页数
	 * @param array $params 搜索参数
	 * @return array
	 */
	public function getDataList($dataType, $dataSegment, $offset = 0, $count = 20, $params = array())
	{
		$res = $this->_api->getList($dataType, $dataSegment, $params, array(
			'offset'	=> $offset,
			'count'		=> $count,
			'order'		=> 'desc',
			'orderby'	=> 'create_time'
		));
		return $res;
	}

	/**
	 * 获取单条数据
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param string $id 
	 * @return array
	 */
	public function getData($dataType, $dataSegment, $id)
	{
		$res = $this->_api->getRow($dataType, $dataSegment, $id);
		return $res;
	}

	/**
	 * 删除数据
	 * @param string $dataType 
	 * @param string $dataSegment 
	 * @param string $id 
	 * @return int
	 */
	public function delData($dataType, $dataSegment, $id)
	{
		$res = $this->_api->delete($dataType, $dataSegment, $id);
		return $res;
	}
}
