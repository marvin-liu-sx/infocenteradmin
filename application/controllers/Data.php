<?php

require_once APPPATH . 'controllers/Base.php';
require_once APPPATH . 'libraries/page.php';

class Data extends Base {
    public function __construct() {
        parent::__construct();
        $this->load->model('Modules');
        $this->load->model('Datas');
    }

    public function index() {
        $pagesize = 20;
        $dt = isset($_GET['dt']) ? $_GET['dt'] : '';
        $ds = isset($_GET['ds']) ? $_GET['ds'] : '';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
        $offset = ($page <= 1 ? 0 : $page - 1) * $pagesize;

        //参数
        if (empty($dt) || empty($ds)) {
            $this->_showMessage('参数错误');
        }

        //过滤数据类型
        if (in_array($dt, $this->_config['deny_data_types'])) {
            $this->_showMessage('不允许对该模型进行操作');
        }

        //检查权限
        $this->_checkPermission($dt, $ds, self::PM_OP_LIST);

        //获取模型
        $module = $this->Modules->getModule($dt, $ds, true);
        if (empty($module)) {
            $this->_showMessage('模型不存在');
        }

        //获限数据
        $params = $_GET;
        unset($params['dt'], $params['ds']);
        foreach ($params as $key => $val) {
            if ($val == '') {
                unset($params[$key]);
            }
        }
        $data = $this->Datas->getDataList($dt, $ds, $offset, $pagesize, $params);

        //翻页
        $url = "/data/index?dt=$dt&ds=$ds";
        $p = new page($url, $data['count'], $pagesize, $page);

        //列表
        $this->assign('page', $p->createHtml());
        $this->assign('module', $module);
        $this->assign('data', $data);
        $this->assign('dt', $dt);
        $this->assign('ds', $ds);
        $this->display('data_index');
    }

    public function add() {
        $dt = isset($_GET['dt']) ? $_GET['dt'] : '';
        $ds = isset($_GET['ds']) ? $_GET['ds'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';

        if (empty($dt) || empty($ds)) {
            $this->_showMessage('参数错误');
        }

        //过滤数据类型
        if (in_array($dt, $this->_config['deny_data_types'])) {
            $this->_showMessage('不允许对该模型进行操作');
        }

        //权限检测
        if ($id) {
            $this->_checkPermission($dt, $ds, self::PM_OP_UPDATE);
        } else {
            $this->_checkPermission($dt, $ds, self::PM_OP_INSERT);
        }

        //获取模型配置
//        $mmod = new \infocm\model\module();
//        $dmod = new \infocm\model\data();
        $module = $this->Modules->getModule($dt, $ds, true);
        if (empty($module)) {
            $this->_showMessage('找不到模型');
        }

        if ($_POST) {
            //获取数据
            $data = array();
            $refer = $_POST['refer'];
            foreach ($module['fields'] as $field => $item) {
                if (isset($_POST['data'][$field])) {
                    $data[$field] = $_POST['data'][$field];
                } else {
                    $data[$field] = '';
                }
                if ($item['required'] && empty($data[$field])) {
                    $this->_showMessage('"' . $item['comment'] . '"为必填项');
                }
            }

            //格式化数据
            foreach ($module['mapping'] as $key => $field) {
                if (isset($data[$field]) &&
                        isset($this->_config['base_fields'][$key]) &&
                        ($fn = $this->_config['base_fields'][$key]['func_format'])) {
                    $data[$field] = $fn($data[$field]);
                }
            }

            //写数据
            if ($id) {
                $result = $this->Datas->editData($dt, $ds, $id, $data);
            } else {
                $newid = isset($_POST['id']) ? $_POST['id'] : '';
                if (!preg_match('/^\w+$/', $newid)) {
                    $this->_showMessage('ID必须为数字、字母或者下划线组成');
                }
                $result = $this->Datas->addData($dt, $ds, $newid, $data);
            }

            if ($result) {
                $this->_showMessage('提交成功', $refer);
            }
            $this->_showMessage('提交失败');
        } else {
            $data = array();
            if ($id) {
                $data = $this->Datas->getData($dt, $ds, $id);
                if (empty($data)) {
                    $this->_showMessage('数据不存在');
                }
            }

            $this->assign('dt', $dt);
            $this->assign('ds', $ds);
            $this->assign('id', $id);
            $this->assign('refer', $_SERVER['HTTP_REFERER']);
            $this->assign('data', $data);
            $this->assign('fields', $module['fields']);
            $this->display('data_add');
        }
    }

    public function del() {
        $dt = isset($_GET['dt']) ? $_GET['dt'] : '';
        $ds = isset($_GET['ds']) ? $_GET['ds'] : '';
        $id = isset($_GET['id']) ? $_GET['id'] : '';
        if (empty($dt) || empty($ds)) {
            $this->_showMessage('参数错误');
        }

        //过滤数据类型
        if (in_array($dt, $this->_config['deny_data_types'])) {
            $this->_showMessage('不允许对该模型进行操作');
        }

        //权限检查
        $this->_checkPermission($dt, $ds, self::PM_OP_DELETE);

        //删除数据
//        $mod = new \infocm\model\data();
        if ($this->Datas->delData($dt, $ds, $id)) {
            $this->_showMessage('删除成功', $_SERVER['HTTP_REFERER']);
        }
        $this->_showMessage('删除失败');
    }

}
