<?php
require_once APPPATH . 'libraries/infoapi.php';
class App extends CI_Model {

    const DATA_TYPE = "infoc_apps";
    const DATA_SEGMENT = "base";

    private $_api = null;

    public function __construct() {
        $this->_api = new infoapi();
    }

    /**
     * 获取应用列表
     * @param int $offset
     * @param int $count
     * @return array
     */
    public function getAppList($offset = 0, $count = 20) {
        $res = $this->_api->getList(self::DATA_TYPE, self::DATA_SEGMENT, array(), array(
            'offset' => $offset,
            'count' => $count,
            'order' => 'desc',
            'orderby' => 'create_time'
        ));
        return $res;
    }

    /**
     * 获取单个应用信息
     * @param string $appid 
     * @return array
     */
    public function getApp($appid) {
        $res = $this->_api->getRow(self::DATA_TYPE, self::DATA_SEGMENT, $appid);
        return $res;
    }

    /**
     * 申请一个应用
     * @param string $appid 
     * @param string $appName 
     * @return int
     */
    public function addApp($appid, $appName) {
        //随机生成SecurityKey
        $securityKey = md5(time() . mt_rand(1000, 9999));
        $res = $this->_api->insert(self::DATA_TYPE, self::DATA_SEGMENT, $appid, array(
            'appname' => $appName,
            'security' => $securityKey
        ));
        return $res;
    }

    /**
     * 编辑一个应用
     * @param string $appid 
     * @param string $appName 
     * @return int
     */
    public function editApp($appid, $appName) {
        $res = $this->_api->update(self::DATA_TYPE, self::DATA_SEGMENT, $appid, array(
            'appname' => $appName
        ));
        return $res;
    }

    /**
     * 删除一个应用
     * @param string $appid 
     * @return int
     */
    public function delApp($appid) {
        $res = $this->_api->delete(self::DATA_TYPE, self::DATA_SEGMENT, $appid);
        return $res;
    }

}
