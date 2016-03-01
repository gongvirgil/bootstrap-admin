<?php
/**
* 
*/
class PictureAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }
    public function index(){
        header("Location:".U('Picture/picture_list'));
    }
    public function album_list(){
        if(!empty($_GET['site_id'])){
            $album = M('album');
            $map['site_id'] = $_GET['site_id'];
            $map['father_id'] = 0;
            $album_list = $album->where($map)->order('sort desc')->select();
            foreach ($album_list as $k => $v) {
                $map['father_id'] = $v['album_id'];
                $album_list[$k]['sub'] = $album->where($map)->order('sort desc')->select();
            }
            $this->site_id = $_GET['site_id'];
            $this->album_list = $album_list;
            $this->display(TMPL_PATH . C("ADMIN_THEME") . '/album_list.html');              
        }else{
            $site = M('site');
            $this->site_list = $site->select();
            $this->display(TMPL_PATH . C("ADMIN_THEME") . '/album_list.html');          
        }
    }
    public function album_add(){
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/album_add.html');   
    }
    public function album_edit(){
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/album_edit.html');   
    }
    public function album_delete(){
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/album_delete.html');   
    } 
    public function picture_list(){
        $this->site_id = $_COOKIE['site_id'];
        $map['site_id'] = $this->site_id;
        $map['article_img'] = array('neq',"");
        import("@.ORG.Util.Page");
        $count = M('article')->where($map)->count();
        $p = new Page($count,32);
        $this->picture_list = M('article')->field('title,article_img')->where($map)->order('create_time desc')->limit($p->firstRow.",".$p->listRows)->select();
        $this->page = $p->show();
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/picture_list.html');   
    } 
    public function picture_add(){
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/picture_add.html');   
    } 
    public function picture_edit(){
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/picture_edit.html');   
    } 
    public function picture_delete(){
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/picture_delete.html');   
    } 

}