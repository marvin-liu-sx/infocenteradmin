<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Base
 *
 * @author yingweiliu
 */
require_once APPPATH . 'libraries/configfc.php';
require_once APPPATH . 'libraries/encrypt.php';

class Base extends CI_Controller {

    //put your code here


    const PM_OP_LIST = 1; //列表权限
    const PM_OP_INSERT = 2; //添加权限
    const PM_OP_UPDATE = 4; //编辑权限
    const PM_OP_DELETE = 8; //删除权限

    protected $_userinfo;
    protected $_config;

    protected $_tpl_path;
    protected $_tpl_vars;

    public function __construct() {
        parent::__construct();
        $this->_tpl_path = APPPATH . 'views/';
        $this->_tpl_vars = array();
        //check login
        $this->_config = ConfigFc::get('infocm')->get('app_config');
        $this->_userinfo = $this->_getLoginInfo();
        $controller = $this->router->class;
        $action = $this->router->method;
        if (empty($this->_userinfo) && (!isset($this->_config['free_login'][$controller]) ||
                !in_array($action, $this->_config['free_login'][$controller]))) {
            if ($controller == 'welcome' && $action == 'index') {
                header('Location: /welcome/login');
            } else {
                $this->_showMessage('您还没有登陆', '/welcome/login');
                die;
            }
        }
    }

//    protected function display($temp, $data = NULL) {
//        $data = $data == null ? $this->_tpl_vars : $data;
//        $this->load->view($temp, $data);
//    }

    /**
     * 显示错误信息
     * @param string $msg 消息内容
     * @param string $url 跳转URL
     * @param int $time 跳间隔秒
     * @return void
     */
    protected function _showMessage($msg, $url = '', $time = 3) {
        $this->assign('msg', $msg);
        $this->assign('url', $url);
        $this->assign('time', $time);
        $this->display('message');
        exit;
    }

    /**
     * 设置登陆信息
     * @return array
     */
    protected function _getLoginInfo() {
        if (isset($_COOKIE[$this->_config['cookie_key']])) {
            $data = $_COOKIE[$this->_config['cookie_key']];
            $encrypt = new encrypt();
            $encrypt->set_key($this->_config['cookie_security_key']);
            $data = $encrypt->decode($data);
            $user = json_decode($data, true);
            return $user;
        }
        return array();
    }

    /**
     * 获取登陆信息
     * @param array $user 
     * @return void
     */
    protected function _setLoginInfo($user) {
        $encrypt = new encrypt();
        $encrypt->set_key($this->_config['cookie_security_key']);
        $data = json_encode($user);
        $code = $encrypt->encode($data);
        setcookie($this->_config['cookie_key'], $code, 0, '/');
    }

    /**
     * 清除登陆信息
     * @return void
     */
    protected function _clearLoginInfo() {
        setcookie($this->_config['cookie_key'], '', 0, '/');
        unset($_COOKIE[$this->_config['cookie_key']]);
    }



    /**
     * 检测当前用户是否有权限
     * @param string $dt 
     * @param string $ds 
     * @param int $type 
     * @return void
     */
    protected function _checkPermission($dt, $ds, $type) {
        if (in_array($this->_userinfo['username'], ['yingweiliu','junli'])) {
            return;
        }

        if (isset($this->_userinfo['permission'][$dt][$ds]) &&
                ($this->_userinfo['permission'][$dt][$ds] & $type)) {
            //nothing todo
        } else {
            $this->_showMessage('您没有权限进行此操作');
        }
    }

    /**
     * 获取配置信息
     * @param $appname 应用程序名
     */
    public function get($appname = 'oap') {
        // 如果已经创建,则直接返回
        static $s_config_array = array();
        if (isset($s_config_array[$appname])) {
            return $s_config_array[$appname];
        }

        $appsetting_address = 'http://config.aplusapi.pptv.com/web/' . $appname .
                '/appsettings-' . SysUtils::getIDCName() .
                '?machine=' . strtolower(php_uname('n')) .
                '&key=' . (time() - 19283746);
        $appsettings = new AppSettings($appname, 'appSettings', $appsetting_address);
  
        $s_config_array[$appname] = $appsettings;
        return $appsettings;
    }

    /**
     * 从应用程序配置中, 获取复合设置对象
     * @param $appname 应用程序名
     * @param $key 复合设置项的名称
     */
    public static function getComposite($appname, $key) {
        $value = self::get($appname)->get($key);
        return new AppSettings('oap', $key, $value);
    }

    /**
     * 视图变量注册
     * @param string $k 
     * @param moxed $v 
     * @return void
     */
    protected function assign($k, $v) {
        $this->_tpl_vars[$k] = $v;
    }

    /**
     * 展示视频
     * @param string $filename 
     * @return void
     */
    protected function display($filename) {
        $file = $this->_tpl_path . $filename.'.php';
        if (file_exists($file)) {
            extract($this->_tpl_vars);
            include $file;
        } else {
            $this->_showMessage('Template file not found');
        }
    }

}
