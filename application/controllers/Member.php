<?php

require_once APPPATH . 'controllers/Base.php';
require_once APPPATH . 'libraries/page.php';

class Member extends Base {

    const PM_DT = "infoc_manager";
    const PM_DS = "base";

    public function __construct() {
        parent::__construct();
        $this->load->model('User');
    }

    public function index() {
        $pagesize = 20;
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 0;
        $offset = ($page <= 1 ? 0 : $page - 1) * $pagesize;

        //权限
        $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_LIST);

        //获取数据
        $data = $this->User->getUserList($offset, $pagesize);

        //翻页
        $url = "/user/index";
        $p = new page($url, $data['count'], $pagesize, $page);

        $this->assign('page', $p->createHtml());
        $this->assign('data', $data);
        $this->display('user_index');
    }

    public function add() {
        $username = isset($_GET['username']) ? $_GET['username'] : '';
        

        //权限检查
        if ($username) {
            $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_UPDATE);
        } else {
            $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_INSERT);
        }

        if ($_POST) {
            $data = array(
                'username' => isset($_POST['username']) ? $_POST['username'] : '',
                'password' => isset($_POST['password']) ? $_POST['password'] : '',
                'permission' => isset($_POST['permission']) ? $_POST['permission'] : array(),
            );

            //权限处理
            $permission = array();
            foreach ((array) $data['permission'] as $dt => $dslist) {
                foreach ((array) $dslist as $ds => $item) {
                    if (($pm = array_sum((array) $item))) {
                        $permission[$dt][$ds] = $pm;
                    }
                }
            }

            //写库
            if ($username) {
                $result = $this->User->editUser($username, $data['password'], $permission);
            } else {
                if (!preg_match('/^\w+$/', $data['username'])) {
                    $this->_showMessage('用户名必须由数字、字母、下划线组成');
                }
                $row = $this->User->getUser($data['username']);
                if ($row) {
                    $this->_showMessage('用户名已存在!');
                }
                $result = $this->User->addUser($data['username'], $data['password'], $permission);
            }
            if ($result) {
                $this->_showMessage('操作成功', '/user');
            }
            $this->_showMessage('操作失败');
        } else {
            $user = array();
            if ($username) {
                $user = $this->User->getUser($username);
                if (!$user) {
                    $this->_showMessage('用户不存在');
                }
            }
            $this->load->model('Modules');
            $module = $this->Modules->getModuleList(array(), 0, 1000);
            $this->assign('module', $module['list']);
            $this->assign('username', $username);
            $this->assign('user', $user);
            $this->display('user_add');
        }
    }

    public function info() {
        if ($_POST) {
            $oldpassword = isset($_POST['oldpassword']) ? $_POST['oldpassword'] : '';
            $password1 = isset($_POST['password1']) ? $_POST['password1'] : '';
            $password2 = isset($_POST['password2']) ? $_POST['password2'] : '';
            if (md5($oldpassword) != $this->_userinfo['password']) {
                $this->_showMessage('旧密码错误');
            }
            if (empty($password1)) {
                $this->_showMessage('新密码不能为空');
            }
            if ($password1 != $password2) {
                $this->_showMessage('两次输入的密码不致');
            }

            if ($this->User->editUser($this->_userinfo['username'], $password1)) {
                $this->_showMessage('保存信息成功');
            }
            $this->_showMessage('保存信息失败');
        } else {
            $this->assign('username', $this->_userinfo['username']);
            $this->display('user_info');
        }
    }

    public function del() {
        $this->_checkPermission(self::PM_DT, self::PM_DS, self::PM_OP_DELETE);
        $username = isset($_GET['username']) ? $_GET['username'] : '';
        if ($username == 'admin') {
            $this->_showMessage('超级管理员账号不能删除');
        }
        if ($username == $this->_userinfo['username']) {
            $this->_showMessage('不能删除正在使用的账号');
        }
       
        $this->User->delUser($username);
        $this->_showMessage('删除成功', '/member');
    }

}
