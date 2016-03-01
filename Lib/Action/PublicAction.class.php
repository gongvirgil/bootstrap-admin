<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Original Author <author@example.com>                        |
// |          Your Name <you@example.com>                                 |
// +----------------------------------------------------------------------+
//
// $Id:$

class PublicAction extends Action {
    public function index() {
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/index.html'); 
    }
    public function verify(){
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }
    public function login() {
        $conn1 = M("webconfig");
        $data = $conn1->find();
        $this->assign('data', $data);
        $this->action = U('Public/checklogin');
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/login.html');
    }
    public function checklogin() {
        import('@.ORG.Util.RBAC');
        if ($_REQUEST['inputUserName'] == "" || $_REQUEST['inputPassword'] == "") {
            $this->error("用户名或者密码不能为空!");
        }
        $username = $_REQUEST['inputUserName'];
        $password = md5($_REQUEST['inputPassword']);
        $map['admin_manager.manager_name'] = $username;
        $conn = D('ManagerView');
        $manager_pwd = $conn->where($map)->getField("manager_pwd");
        $status      = $conn->where($map)->getField("status");
        $manager_id  = $conn->where($map)->getField("manager_id");
        $enable      = $conn->where($map)->getField("enable");
        $access      = $conn->where($map)->getField("access");
        if ($manager_id != "1" && $status != "1") {
                $this->error("对不起,您的账号已经被禁用,请联系管理员");
        }
        $error = M("admin_login_error");
        // 获取当前时间 查询同ip在最近5分钟登陆失败次数
		/*        
        $time = time() - 5 * 60;
        $count = $error->where("username='" . $_REQUEST['loginname'] . "' and ip ='" . get_client_ip() . "' and time >" . $time)->count();
        $conn1 = M("webconfig");
        $data = $conn1->find();
        if ($count >= $data['max_error']) {
            $this->error("由于您多次登陆失败，系统已经锁定该用户，请5分钟之后尝试！");
        }
        */
        if ($password != $manager_pwd) {
            $error_log['input_manager_name'] = trim($_REQUEST['inputUserName']);
            $error_log['input_manager_pwd']  = trim($_REQUEST['inputPassword']);
            $error_log['os']                 = get_client_os();
            $error_log['time']               = time();
            $error_log['ip']                 = get_client_ip();
            $error->data($error_log)->add();
            $this->error("登陆失败,请检查您的输入!");
        } else {
            $role_id = $conn->where($map)->getField("role_id");
            $role_name = $conn->where($map)->getField("role_name");
            $_SESSION['user'] = $username;
            $_SESSION['role_id'] = $role_id;
            $_SESSION['group'] = $role_name;
            $_SESSION['access'] = $access;
            if ($username == 'admin') {
                $_SESSION['administrator'] = true;
            }
            // 保存登陆记录
            $log['login_ip'] = get_client_ip();
            $log['os'] = get_client_os();
            $log['login_time'] = time();
            $conn->where($map)->save($log);
            $conn->where($map)->setInc('login_count', '1');
            RBAC::saveAccessList();
            $this->success('登陆成功,系统检测到您为' . $role_name . '  正在跳转...', C('ADMIN_PATH'));
        }
    }
    public function loginout(){
		if (isset($_SESSION['user'])) {
		    unset($_SESSION['user']);
		    unset($_SESSION['group']);
		    session_destroy();
		    redirect($_SERVER['HTTP_REFERER']);
		}
		redirect($_SERVER['HTTP_REFERER']);
    }
}
?>
