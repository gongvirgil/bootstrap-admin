<?php
/**
* 
*/
class IndexAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }
    public function index(){
        $system = explode(" ", php_uname());
        $system_info['os'] = $system[0];
        $system_info['kernel_version'] = '/' == DIRECTORY_SEPARATOR ? $system[2] : $system[1];
        $system_info['server_software'] = $_SERVER['SERVER_SOFTWARE'];
        $system_info['mysql_info'] = mysql_get_server_info();
        $system_info['upload_max_filesize'] = ini_get('upload_max_filesize');
        $this->system_info = $system_info;
        $this->manager_info = $this->get_manager_info($_SESSION['user'],"manager_name,role_name,login_time,login_ip");
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/admin_index.html');
    }
}