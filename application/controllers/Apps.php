<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once APPPATH . 'controllers/Base.php';
require_once APPPATH . 'libraries/page.php';

class Apps extends Base {

    const PM_DT = "infoc_apps";
    const PM_DS = "base";
    public function __construct() {
        parent::__construct();
        $this->load->model('App');
    }

    public function index() {
        $pagesize = 20;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
        $offset = ($page <= 1 ? 0 : $page - 1) * $pagesize;

        //权限		
        $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_LIST);

        //获取数据
//        $mod = new \infocm\model\apps();
        $data = $this->App->getAppList($offset, $pagesize);

        //翻页
        $url = "/apps/index";
        $p = new page($url, $data['count'], $pagesize, $page);

        $this->assign('page', $p->createHtml());
        $this->assign('data', $data);
        $this->display('apps_index');
    }

    public function add() {
        $appid = isset($_GET['appid']) ? $_GET['appid'] : '';
//        $mod = new \infocm\model\apps();

        //权限检查
        if ($appid) {
            $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_UPDATE);
        } else {
            $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_INSERT);
        }

        if ($_POST) {
            $data = array(
                'appid' => isset($_POST['appid']) ? $_POST['appid'] : '',
                'appname' => isset($_POST['appname']) ? $_POST['appname'] : ''
            );

            //数据检查
            if (empty($appid)) {
                if (empty($data['appid'])) {
                    $this->_showMessage('应用标识不能为空');
                }
                if (!preg_match('/^\w+$/', $data['appid'])) {
                    $this->_showMessage('应用标识格式错误');
                }
            }
            if (empty($data['appname'])) {
                $this->_showMessage('应用名称不能为空');
            }

            if ($appid) {
                $result =  $this->App->editApp($appid, $data['appname']);
            } else {
                //检查重复
                echo '****';
                var_dump($this->App->getApp($data['appid']));
                echo '----';
                if ( $this->App->getApp($data['appid'])) {
                    $this->_showMessage('应用标识已存在');
                }
                $result =  $this->App->addApp($data['appid'], $data['appname']);
            }
            if ($result) {
                $this->_showMessage('操作成功', '/apps');
            }
            $this->_showMessage('操作失败');
        } else {
            $row = array();
            if ($appid && !($row =  $this->App->getApp($appid))) {
                $this->_showMessage('该应用不存在');
            }
            $this->assign('appid', $appid);
            $this->assign('app', $row);
            $this->display('apps_add');
        }
    }

    public function del() {
        $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_DELETE);
        $appid = isset($_GET['appid']) ? $_GET['appid'] : '';
//        $mod = new \infocm\model\apps();
        if ( $this->App->delApp($appid)) {
            $this->_showMessage('删除应用成功', $_SERVER['HTTP_REFERER']);
        }
        $this->_showMessage('操作失败');
    }

}
