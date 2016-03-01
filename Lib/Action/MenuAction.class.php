<?php
/**
* 
*/
class MenuAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }
    public function index(){
        header("Location:".U('Menu/menu_list'));
    }
    public function menu_add() {
        $menu = M('admin_menu');
        $map['father_id'] = 0;
        $map['isshow'] = 1;
        $top_menu_list = $menu->where($map)->order('menu_id asc')->select();
        unset($map);
        foreach ($top_menu_list as $k => $v) {
            $map['father_id'] = $v['menu_id'];
            $top_menu_list[$k]['son'] = $menu->where($map)->order("sort asc")->select();
        }
        $this->top_menu_list = $top_menu_list;
        unset($map);
        if($_REQUEST['dosubmit']==1){    
            $map['menu_name']   = trim($_REQUEST['menu_name']); 
            $map['menu_method'] = trim($_REQUEST['menu_method']); 
            $map['father_id']   = trim($_REQUEST['father_id']);
            $map['sort']        = trim($_REQUEST['sort']); 
            $map['isshow']      = trim($_REQUEST['isshow']);
            if($map['menu_name'] == "" || $map['menu_method'] == ""){
                $this->error('栏目名称或方法不能为空！');
            }
            if($menu->where("menu_name='".$map['menu_name']."'")->find()){
                $this->error('栏目名称已存在！');
            }
            if($menu->where("menu_method='".$map['menu_method']."'")->find()){
                $this->error('栏目方法已存在！');
            }
            if($menu->add($map)){
                $this->success('添加成功！',U('Admin/menu_list'));
            }else{
                $this->success('添加失败！');
            }
        }
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/menu_add.html');
    }
    public function menu_list() {
        if($_REQUEST['dosubmit']==1){
            foreach ( $_REQUEST['sort'] as $menu_id => $sort ) {
                $where['menu_id'] = $menu_id;
                $map['sort'] = $sort;
                M('admin_menu')->where ( $where )->save( $map );
            }
            $this->success ( '操作成功！' );
        }
        $this->menu_list = $this->get_menu_list();
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/menu_list.html');
    }
    public function  menu_edit() {
        $menu = M('admin_menu');
        $map['father_id'] = 0;
        $map['isshow'] = 1;
        $top_menu_list = $menu->where($map)->order('sort asc')->select();
        unset($map);
        foreach ($top_menu_list as $k => $v) {
            $map['father_id'] = $v['menu_id'];
            $top_menu_list[$k]['son'] = $menu->where($map)->order("sort asc")->select();
        }
        $this->top_menu_list = $top_menu_list;
        unset($map);
        $map['menu_id'] = $_GET['menu_id'];
        $this->info = $menu->where($map)->find();
        unset($map);
        if($_REQUEST['dosubmit']==1){

            $where['menu_id']   = trim($_REQUEST['menu_id']); 
            $map['father_id']   = trim($_REQUEST['father_id']); 
            $map['menu_name']   = trim($_REQUEST['menu_name']); 
            $map['menu_method'] = trim($_REQUEST['menu_method']); 
            $map['sort']        = trim($_REQUEST['sort']); 
            $map['isshow']      = trim($_REQUEST['isshow']);
            if($menu->where($where)->save($map)){
                $this->success('修改成功！',U('Admin/menu_list'));
            }else{
                $this->success('修改失败！');
            }
        }
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/menu_edit.html');
    }
    public function menu_delete() {
        $menu = M('admin_menu');
    }
}