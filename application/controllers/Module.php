<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPPATH . 'controllers/Base.php';
require_once APPPATH . 'libraries/page.php';
class Module extends Base {

    const PM_DT = "infoc_module";
    const PM_DS = "base";
    public function __construct() {
        parent::__construct();
        $this->load->model('Modules');
        $this->load->model('User');
    }

    public function index() {
        $pagesize = 20;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
        $offset = ($page <= 1 ? 0 : $page - 1) * $pagesize;
        $name = isset($_GET['name']) ? $_GET['name'] : '';
        $dt = isset($_GET['dt']) ? $_GET['dt'] : '';
        $ds = isset($_GET['ds']) ? $_GET['ds'] : '';
        $params = array();

        //权限
        $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_LIST);

        //搜索条件
        if (isset($name{0})) {
            $params['name'] = $name;
        }
        if (isset($dt{0})) {
            $params['dt'] = $dt;
        }
        if (isset($ds{0})) {
            $params['ds'] = $ds;
        }

        //获取数据
        $data = $this->Modules->getModuleList($params, $offset, $pagesize);

        //翻页
        $url = "/module/index?name=$name&dt=$dt&ds=$ds";
        $p = new page($url, $data['count'], $pagesize, $page);

        $this->assign('page', $p->createHtml());
        $this->assign('name', $name);
        $this->assign('dt', $dt);
        $this->assign('ds', $ds);
        $this->assign('data', $data);
        $this->assign('deny_data_types', $this->_config['deny_data_types']);
        $this->display('module_index');
    }

    public function add() {
        $dt = isset($_GET['dt']) ? $_GET['dt'] : '';
        $ds = isset($_GET['ds']) ? $_GET['ds'] : '';

        //权限检查
        if ($dt && $ds) {
            $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_UPDATE);
            $this->_checkPermission($dt, $ds, self::PM_OP_UPDATE);
        } else {
            $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_INSERT);
        }

        if ($_POST) {
            //类型检测
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            if (empty($name)) {
                $this->_showMessage('模型名称不能为空');
            }

            if (empty($dt)) {
                //标识
                $mark = isset($_POST['mark']) ? trim($_POST['mark']) : '';
                if (empty($mark)) {
                    $this->_showMessage('模型标识不能为空');
                }
                if (in_array($mark, $this->_config['deny_data_types'])) {
                    $this->_showMessage('该标识不允许使用');
                }
                if (preg_match('/^\w+$/', $mark) == false) {
                    $this->_showMessage('模型标识必须由数字、字母、下划线组成');
                }

                //数据段
                $segment = isset($_POST['segment']) ? trim($_POST['segment']) : '';
                if (empty($segment)) {
                    $this->_showMessage('数据段不能为空');
                }
                if (preg_match('/^\w+$/', $segment) == false) {
                    $this->_showMessage('数据段必须由数字、字母、下划线组成');
                }

                //重复检测
                $row = $this->Modules->getModule($mark, $segment);
                if ($row) {
                    $this->_showMessage('模型已经存在');
                }
            }

            //处理扩展类型
            $input = array('field', 'comment', 'control', 'default', 'required', 'tip', 'options');
            $deny = array('id', 'dt', 'ds', 'create_time', 'update_time');
            $fields = array();
            if (isset($_POST['ext_field'])) {
                foreach ($_POST['ext_field'] as $key => $v) {
                    if (!preg_match('/^\w+$/', $v)) {
                        $this->_showMessage('字段标识必须由数字、字母、下划线组成');
                    }
                    if (in_array($v, $deny)) {
                        $this->_showMessage("系统字段'$v'不能使用");
                    }
                    $item = array();
                    foreach ($input as $in) {
                        $item[$in] = isset($_POST['ext_' . $in][$key]) ? $_POST['ext_' . $in][$key] : '';
                    }
                    $fields[$item['field']] = $item;
                }
            }

            //映射
            $mapping = array();
            foreach ($this->_config['base_fields'] as $key => $val) {
                if (isset($_POST[$key]) && ($v = trim($_POST[$key])) && isset($fields[$v])) {
                    $mapping[$key] = $v;
                }
            }

            //显示
            $show = isset($_POST['show']) ? $_POST['show'] : '';
            $show_list = explode(',', $show);
            foreach ($show_list as $key => $val) {
                if (!isset($fields[$val])) {
                    unset($show_list[$key]);
                }
            }
            $show = implode(',', $show_list);

            //数据提交
            $data = array(
                'name' => $name,
                'show' => $show,
                'mapping' => $mapping,
                'fields' => $fields,
            );
            if ($dt && $ds) {
                $result = $this->Modules->editModule($dt, $ds, $data); //编辑
            } else {
                $result = $this->Modules->addModule($mark, $segment, $data); //创建
                //添加默认对该模型的操作权限
                if (($this->_userinfo['username'] != 'admin')) {
                    //更新库
//                    $modUser = new \infocm\model\user();
                    $this->load->model('User');
                    $user = $this->User->addPermission($this->_userinfo['username'], $mark, $segment, self::PM_OP_LIST | self::PM_OP_INSERT | self::PM_OP_UPDATE | self::PM_OP_DELETE);
                    //更新本地cookie
                    $this->_setLoginInfo($user);
                }
            }
            if ($result) {
                $this->_showMessage('提交成功', '/module');
            }
            $this->_showMessage('提交失败');
        } else {
            $row = array();
            if ($dt && $ds) {
                $row = $this->Modules->getModule($dt, $ds);
                if (empty($row)) {
                    $this->_showMessage('模型不存在');
                }
            }
            $this->assign('dt', $dt);
            $this->assign('ds', $ds);
            $this->assign('row', $row);
            $this->assign('base_fields', $this->_config['base_fields']);
            $this->display("module_add");
        }
    }

    public function del() {
        $dt = isset($_GET['dt']) ? $_GET['dt'] : '';
        $ds = isset($_GET['ds']) ? $_GET['ds'] : '';
        if (in_array($dt, $this->_config['deny_data_types'])) {
            $this->_showMessage('系统模型不能删除');
        }

        $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_DELETE);
        $this->_checkPermission($dt, $ds, self::PM_OP_DELETE);

        if ($this->Modules->delModule($dt, $ds)) {
            $this->_showMessage('删除成功', '/module');
        } else {
            $this->_showMessage('删除失败');
        }
    }

}
