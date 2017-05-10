<?php

require_once APPPATH . 'libraries/infoapi.php';

class Modules extends CI_Model {

    const DATA_TYPE = 'infoc_module';

    private $_api = null;

    public function __construct() {
        $this->_api = new infoapi();
    }

    /**
     * 获取模型列表
     * @param array $params
     * @param int $offset
     * @param int $count
     * @return array
     */
    public function getModuleList($params = array(), $offset = 0, $count = 20) {
        $where = array();
        $dt = '';
        if (isset($params['dt'])) {
            $dt = $params['dt'];
        }
        if (isset($params['ds'])) {
            $where['ids'] = $params['ds'];
        }
        if (isset($params['name'])) {
            $where['name'] = $params['name'];
        }
        $res = $this->_api->getList(self::DATA_TYPE, $dt, $where, array(
            'offset' => $offset,
            'count' => $count,
            'order' => 'desc',
            'orderby' => 'create_time'
        ));
        return $res;
    }

    /**
     * 添加模型
     * @param string $dt 
     * @param string $ds 
     * @param array $data 
     * @return int
     */
    public function addModule($dt, $ds, $data) {
        $res = $this->_api->insert(self::DATA_TYPE, $dt, $ds, $data);
        return $res;
    }

    /**
     * 编辑模型
     * @param string $dt 
     * @param string $ds 
     * @param array $data 
     * @return int
     */
    public function editModule($dt, $ds, $data) {
        $res = $this->_api->update(self::DATA_TYPE, $dt, $ds, $data);
        return $res;
    }

    /**
     * 获取模型
     * @param string $dt 
     * @param string $ds 
     * @param bool $option_format 
     * @return array
     */
    public function getModule($dt, $ds, $format = false) {
        $res = $this->_api->getRow(self::DATA_TYPE, $dt, $ds);
        if ($res && $format) {
            foreach ($res['fields'] as $key => $item) {
                if (($item['control'] == 'select') ||
                        ($item['control'] == 'checkbox') ||
                        ($item['control'] == 'radio')) {
                    $option_arr = explode('|', $item['options']);
                    $options = array();
                    foreach ($option_arr as $opt) {
                        $val = explode(',', $opt);
                        if (!empty($val[0]) || !empty($val[1])) {
                            $options[$val[0]] = isset($val[1]) ? $val[1] : '';
                        }
                    }
                    $res['fields'][$key]['options'] = $options;
                } else {
                    $res['fields'][$key]['options'] = array();
                }
            }
            $res['show'] = isset($res['show']) ? explode(',', $res['show']) : array();
        }
        return $res;
    }

    /**
     * 删除模型
     * @param string $dt 
     * @param string $ds 
     * @return int
     */
    public function delModule($dt, $ds) {
        $res = $this->_api->delete(self::DATA_TYPE, $dt, $ds);
        return $res;
    }

}
