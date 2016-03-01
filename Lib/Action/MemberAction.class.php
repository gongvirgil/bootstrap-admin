<?php
/**
* 
*/
class MemberAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }
    public function index(){
        header("Location:".U('Member/member_list'));
    }
    public function member_list(){
        $this->member_list = M('member')->order('uid desc')->select();
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/member_list.html');
    }
    public function member_edit(){
        $member = M('admin_member');
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
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/member_edit.html');
    }
}