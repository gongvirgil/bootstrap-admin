<?php
/**
* 
*/
class ManagerAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }
    public function index(){
        header("Location:".U('Manager/manager_list'));
    }
    public function role_list(){
        $role = M('admin_role');
        $this->role_list = $role->select();
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/role_list.html');
    } 
    public function role_add(){
        $role = M('admin_role');
        if($_REQUEST['dosubmit']==1){    
            $map['role_name']   = trim($_REQUEST['role_name']); 
            $map['description'] = trim($_REQUEST['description']); 
            $map['enable']      = trim($_REQUEST['enable']);
            if($map['role_name'] == "" || $map['description'] == ""){
                $this->error('角色名或描述不能为空！');
            }
            if($role->where("role_name='".$map['role_name']."'")->find()){
                $this->error('角色名已存在！');
            }
            if($role->add($map)){
                $this->success('角色添加成功！');
            }else{
                $this->error('角色添加失败！');
            }
        }
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/role_add.html');    
    }
    public function role_edit(){
        $role = M('admin_role');
        $map['role_id'] = $_GET['role_id'];
        $this->info = $role->where($map)->find();
        unset($map);
        if($_REQUEST['dosubmit']==1){
            $where['role_id']   = trim($_REQUEST['role_id']); 
            $map['role_name']   = trim($_REQUEST['role_name']); 
            $map['description']        = trim($_REQUEST['description']); 
            $map['enable']      = trim($_REQUEST['enable']);
            if($role->where($where)->save($map)){
                $this->success('修改成功！');
            }else{
                $this->error('修改失败！');
            }
        }
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/role_edit.html');    
    }
    public function role_delete(){
        $role = M('admin_role');
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/role_delete.html');    
    }
    public function role_access_set(){ 
        $this->role_id = trim($_GET['role_id']);
        $access = M('admin_role_access');
        $map['role_id'] = trim($_GET['role_id']);
        $list = $access->where($map)->order ( 'role_id asc' )->select();
        unset($map);
        $menu_list = parent::get_menu_list();
        foreach ($menu_list as $k => $v) {
            foreach ($list as $k1 => $v1) {
                if($v1['menu_id']==$v['menu_id']) $menu_list[$k]['flag'] = 1;
            }
            foreach ($v['son'] as $k1 => $v1) {
                foreach ($list as $k2 => $v2) {
                    if($v2['menu_id']==$v1['menu_id']) $menu_list[$k]['son'][$k1]['flag'] = 1;
                }
                foreach ($v1['son'] as $k2 => $v2) {
                    foreach ($list as $k3 => $v3) {
                        if($v3['menu_id']==$v2['menu_id']) $menu_list[$k]['son'][$k1]['son'][$k2]['flag'] = 1;
                    }
                }
            }
        }
        //exit(json_encode($menu_list));
        $this->assign('menu_list',$menu_list);
        unset($map);
        $role = M('admin_role');

        if($_REQUEST['dosubmit']==1){
            $map['role_id'] = trim($_REQUEST['role_id']);
            $access->where ( $map )->delete();
            foreach ($_REQUEST['menuid'] as $_id => $menu_id) {
                $map['menu_id'] = $menu_id;
                $map['role_id'] = trim($_REQUEST['role_id']);
                $flag = $access->data($map)->add();
                unset($map);
                if(!$flag) $this->error('设置失败'.mysql_error());
            }
            $this->success('设置成功！');           
        }
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/role_access_set.html');           
    }
    public function manager_list(){
        $manager = D('ManagerView');
        $this->manager_list = $manager->select();
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/manager_list.html');
    }
    public function manager_add(){
        $manager = M('admin_manager');
        $role = M('admin_role');
        $map['enable'] = 1;
        $this->role_list = $role->where($map)->select();
        if($_REQUEST['dosubmit']==1){    
            $map['manager_name']  = trim($_REQUEST['manager_name']); 
            $map['manager_pwd']   = md5(trim($_REQUEST['manager_pwd'])); 
            $map['manager_email'] = trim($_REQUEST['manager_email']); 
            $map['role_id']       = trim($_REQUEST['role_id']);
            $map['status']        = trim($_REQUEST['status']);
            $map['access']        = trim($_REQUEST['access']);
            if($map['manager_name'] == "" || $map['manager_pwd'] == ""){
                $this->error('管理员名称或密码不能为空！');
            }
            if($map['manager_email'] == ""){
                $this->error('管理员邮箱不能为空！');
            }
            if($map['role_id'] == ""){
                $this->error('管理员所属角色不能为空！'); 
            }
            if($manager->where("manager_name='".$map['manager_name']."'")->find()){
                $this->error('管理员已存在！');
            }
            if($manager->add($map)){
                $this->success('管理员添加成功！');
            }else{
                $this->success('管理员添加失败！');
            }
        }
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/manager_add.html');
    }
    public function manager_edit(){
        $manager = M('admin_manager');
        $map['manager_id'] = $_GET['manager_id'];
        $this->info = $manager->where($map)->find();
        unset($map);
        $role = M('admin_role');
        $map['enable'] = 1;
        $this->role_list = $role->where($map)->select();
        unset($map);
        if($_REQUEST['dosubmit']==1){
            $where['manager_id']   = trim($_REQUEST['manager_id']); 
            $map['manager_name']  = trim($_REQUEST['manager_name']); 
            $map['manager_pwd']   = md5(trim($_REQUEST['manager_pwd'])); 
            $map['manager_email'] = trim($_REQUEST['manager_email']); 
            $map['role_id']       = trim($_REQUEST['role_id']);
            $map['status']        = trim($_REQUEST['status']);
            $map['access']        = trim($_REQUEST['access']);
            if($map['manager_name'] == "" || $map['manager_pwd'] == ""){
                $this->error('管理员名称或密码不能为空！');
            }
            if($map['manager_email'] == ""){
                $this->error('管理员邮箱不能为空！');
            }
            if($map['role_id'] == ""){
                $this->error('管理员所属角色不能为空！'); 
            }
            if($manager->where($where)->save($map)){
                $this->success('修改成功！');
            }else{
                $this->success('修改失败！');
            }
        } 
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/manager_edit.html');
    }
    public function manager_delete(){
        $manage = M('admin_manager');
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/manager_delete.html');
    }
}