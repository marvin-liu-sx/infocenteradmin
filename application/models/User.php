<?php
require_once APPPATH . 'libraries/infoapi.php';
class User extends CI_Model
{
	const DATA_TYPE				= "infoc_manager";
	const DATA_SEGMENT			= "base";
	private $_api 				= null;

	public function __construct()
	{
		$this->_api = new infoapi();
	}

	/**
	 * 获取用户列表
	 * @param int $offset
	 * @param int $count
	 * @return array
	 */
	public function getUserList($offset = 0, $count = 20)
	{
		$res = $this->_api->getList(self::DATA_TYPE, self::DATA_SEGMENT, array(), array(
			'offset'	=> $offset,
			'count'		=> $count,
			'order'		=> 'desc',
			'orderby'	=> 'create_time'
		));
		return $res;
	}

	/**
	 * 获取单个用户
	 * @param string $username 
	 * @return array
	 */
	public function getUser($username)
	{
		$res = $this->_api->getRow(self::DATA_TYPE, self::DATA_SEGMENT, $username);
		$user = array();
		if($res){
			$user = array(
				'username'		=> $res['id'],
				'password'		=> $res['password'],
				'permission'	=> $res['permission'],
				'create_time'	=> $res['create_time'],
				'update_time'	=> $res['update_time'],
			);
		}
		return $user;
	}

	/**
	 * 添加账户
	 * @param string $username 
	 * @param string $password 
	 * @param array $permission 
	 * @return int
	 */
	public function addUser($username, $password, $permission)
	{
		$res = $this->_api->insert(self::DATA_TYPE, self::DATA_SEGMENT, $username, array(
			'password' => md5($password),
			'permission' => $permission
		));
		return $res;
	}

	/**
	 * 为用户添加某个权限
	 * @param string $username 
	 * @param string $dt 
	 * @param string $ds 
	 * @param int $permission 
	 * @return int
	 */
	public function addPermission($username, $dt, $ds, $permission)
	{
		$user = $this->getuser($username);
		if($user){
			if($permission){
				$user['permission'][$dt][$ds] = $permission;
				$result = $this->editUser($username, null, $user['permission']);
			}
			return $user;
		}
		return false;
	}

	/**
	 * 编辑用户信息
	 * @param string $username 
	 * @param string $password 
	 * @param string $permission 
	 * @return int
	 */
	public function editUser($username, $password = null, $permission = null)
	{
		$data = array();
		if($permission){
			$data['permission'] = $permission;
		}
		if($password){
			$data['password'] = md5($password);
		}
		$res = $this->_api->update(self::DATA_TYPE, self::DATA_SEGMENT, $username, $data);
		return $res;
	}

	/**
	 * 删除用户
	 * @param string $username 
	 * @return int
	 */
	public function delUser($username)
	{
		$res = $this->_api->delete(self::DATA_TYPE, self::DATA_SEGMENT, $username);
		return $res;
	}
}