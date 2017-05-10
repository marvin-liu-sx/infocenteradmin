<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once APPPATH . 'controllers/Base.php';

class Welcome extends Base {

    public function index() {
        $this->display('index');
    }

    public function menu() {
        $this->assign('username', $this->_userinfo['username']);
        $this->display('menu');
    }

    public function login() {
        $this->load->library('cas');
        $this->cas->force_auth();
        $userInfo = $this->cas->user();
        if ((!empty($userInfo)) && is_object($userInfo)) {
            $account = array('username' => $userInfo->attributes['sAMAccountName'], 'name' => $userInfo->attributes['Name']);
            $this->load->model('User');
            $user = $this->User->getUser($account['username']);
            $user = array_merge($user, $account);
            $this->_setLoginInfo($user);
            header('Location: /welcome/index');
        }
    }

    public function login2() {
        error_reporting(0);
        if ($_POST) {
            $user = isset($_POST['username']) ? $_POST['username'] : "";
            $pass = isset($_POST['password']) ? $_POST['password'] : "";
            if (empty($user) || empty($pass)) {
                $return['code'] = 0;
                $return['result'] = "用户名或密码不能为空";
                $this->_showMessage('用户名或者密码错误', '/welcome/login');
                exit();
            }

            $var_ldap_host = "synacast.local";
            $var_ldap_user = $user . "@synacast.local";
            $var_ldap_pswd = stripcslashes($pass);
            $var_ldap_ad = \ldap_connect($var_ldap_host); //Connect to ad
            if (false == $var_ldap_ad) {
                $return['code'] = 0;
                $return['result'] = "connect fail";
                $this->_showMessage('系统无法连接', '/welcome/login');
                exit();
            }

            restore_error_handler();
            restore_exception_handler();

            ldap_set_option($var_ldap_ad, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($var_ldap_ad, LDAP_OPT_REFERRALS, 0);

            $var_ldap_bd = \ldap_bind($var_ldap_ad, $var_ldap_user, $var_ldap_pswd);
            //16091478
//            var_dump($var_ldap_pswd);
//            var_dump($var_ldap_bd);die;
            if (false == $var_ldap_bd) {
                $this->_showMessage('用户名或者密码错误', '/welcome/login');
                exit();
            }


            //get info
//			$attrs = array("displayname", "mail", "department", "mailNickname");
            $attrs = array("sAMAccountName", "displayname", "department", "mailNickname", "Name");
            $filter = "(&(objectClass=person)(mailNickname=" . $user . "))";
            $search = \ldap_search($var_ldap_ad, 'dc=synacast,dc=local', $filter, $attrs);
            if (false == $search) {
                $return['code'] = 0;
                $return['result'] = "search fail";
                echo json_encode($return);
                return false;
            }
            $entries = \ldap_get_entries($var_ldap_ad, $search);
            \ldap_close($var_ldap_ad);


            if ((!empty($entries)) && (!empty($entries[0]))) {
                $account = array('username' => $entries[0]['samaccountname'][0], 'name' => $entries[0]['displayname'][0]);
                $this->load->model('User');
                $user = $this->User->getUser($account['username']);
                $user = array_merge($user, $account);

                $this->_setLoginInfo($user);
           
                header('Location: /welcome/index');
            }
        } else {
            $this->display('login');
        }
    }

    public function logout() {
        $this->load->library('cas');
        $this->_clearLoginInfo();
        $user = $this->cas->logout();
        header('Location: /welcome/login');
    }

    public function captcha() {
        $captcha = new \infocm\utils\captcha();
        $captcha->out();
    }

}
