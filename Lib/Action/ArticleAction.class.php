<?php
/**
* 
*/
class ArticleAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }
    public function index(){
        header("Location:".U('Article/article_list'));
    }
    public function column_list(){

        $column = M('column');
        $map['site_id'] = 1;
        $map['father_id'] = 0;
        $column_list = $column->where($map)->order('sort desc')->select();
        foreach ($column_list as $k => $v) {
            $map['father_id'] = $v['column_id'];
            $column_list[$k]['sub'] = $column->where($map)->order('sort desc')->select();
        }
        $this->site_id = 1;
        $this->assign('column_list',$column_list);
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/column_list.html');

        /*
        if(!empty($_GET['site_id'])){              
        }else{
            $site = M('site');
            $this->site_list = $site->select();
            $this->display(TMPL_PATH . C("ADMIN_THEME") . '/site_list.html');          
        }
        */
 
    }
    public function  column_add(){
        if($_REQUEST['dosubmit']==1){
            $map['site_id']     = $site_id = trim($_REQUEST['site_id']);
            $map['column_name'] = trim($_REQUEST['column_name']);
            $map['father_id']   = trim($_REQUEST['father_id']);
            $map['keywords']    = trim($_REQUEST['keywords']);
            $map['description'] = trim($_REQUEST['description']);
            $map['sort']        = trim($_REQUEST['sort']);
            $map['isshow']      = trim($_REQUEST['isshow']);
            $result = M('column')->add($map);
            if($result){
                $this->success('添加成功','./index.php?m=Article&a=column_list&site_id='.$site_id);
            }else{
                $this->success('添加失败','./index.php?m=Article&a=column_list&site_id='.$site_id);
            }
        }

        $this->site_id = trim($_GET['site_id']);
        $map['site_id'] = trim($_GET['site_id']);
        $this->site_info = M('site')->where($map)->find();
        $map['father_id'] = 0;
        $this->top_column_list = M('column')->where($map)->select();


        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/column_add.html');    
    }
    public function  column_edit(){

        if($_REQUEST['dosubmit']==1){
            $site_id            = trim($_REQUEST['site_id']);
            $map['column_name'] = trim($_REQUEST['column_name']);
            $map['father_id']   = trim($_REQUEST['father_id']);
            $map['keywords']    = trim($_REQUEST['keywords']);
            $map['description'] = trim($_REQUEST['description']);
            $map['sort']        = trim($_REQUEST['sort']);
            $map['isshow']      = trim($_REQUEST['isshow']);
            $where['column_id'] = trim($_REQUEST['column_id']);
            $result = M('column')->where($where)->save($map);
            if($result){
                $this->success('修改成功','./index.php?m=Article&a=column_list&site_id='.$site_id);
            }else{
                $this->success('修改失败','./index.php?m=Article&a=column_list&site_id='.$site_id);
            }
        }

        $this->site_id = trim($_GET['site_id']);
        $map['site_id'] = trim($_GET['site_id']);
        $this->site_info = M('site')->where($map)->find();
        $map['father_id'] = 0;
        $this->top_column_list = M('column')->where($map)->select();
        unset($map);
        $map['column_id'] = trim($_GET['column_id']);
        $this->info = M('column')->where($map)->find();

        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/column_edit.html');    
    }  
    public function  column_delete(){
        $column = M('column');
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/column_delete.html');    
    }
    public function article_list(){
        $this->site_id = $_COOKIE['site_id'];
        $map['site_id'] = $this->site_id;
        //$this->article_list = M('article')->where($map)->select();
        import("@.ORG.Util.Page");
        $count = M('article')->where($map)->count();
        $p = new Page($count,10);
        $this->article_list = M('article')->where($map)->order('create_time desc')->limit($p->firstRow.",".$p->listRows)->select();
        $this->page = $p->show();
        
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/article_list.html');    
    }
    public function article_add(){
        if($_REQUEST['dosubmit']==1){
            $map['site_id'] = trim($_REQUEST['site_id']);
            $map['column_id'] = trim($_REQUEST['column_id']);
            $map['title'] = trim($_REQUEST['title']);
            $map['short_title'] = trim($_REQUEST['short_title']);
            $map['author'] = trim($_REQUEST['author']);
            $map['from'] = trim($_REQUEST['from']);
            $map['keywords'] = trim($_REQUEST['keywords']);
            $map['description'] = trim($_REQUEST['description']);
            $map['weight'] = trim($_REQUEST['weight']);
            $map['hits'] = trim($_REQUEST['hits']);

            $a = trim($_REQUEST['isrec'])==1?'a':'';
            $b = trim($_REQUEST['isflash'])==1?'b':'';
            $c = trim($_REQUEST['istop'])==1?'c':'';
            $d = trim($_REQUEST['isbold'])==1?'d':'';
            $map['attributes'] = $a.",".$b.",".$c.",".$d;

            $map['tags'] = trim($_REQUEST['tags']);


            if($_REQUEST['pic']){
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();// 实例化上传类
                $upload->maxSize  = 3145728 ;// 设置附件上传大小
                $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->savePath =  './Public/Uploads/';// 设置附件上传目录
                if(!$upload->upload()) {// 上传错误提示错误信息
                    $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                    $info =  $upload->getUploadFileInfo();
                    $map['pic'] = $info[0]['savepath'].$info[0]['savename'];
                }
            }

            $map['comment_open'] = trim($_REQUEST['comment_open']);
            $map['create_time'] = strtotime(trim($_REQUEST['create_time']));
            $map['update_time'] = strtotime(trim($_REQUEST['update_time']));
            $map['content'] = trim($_REQUEST['content']);
            $result = M('article')->add($map);
            if($result){
                $this->success('添加成功');
            }else{
                $this->success('添加失败');
            }
        }
        $this->site_id = $_GET['site_id'];
        $map['site_id'] = $_GET['site_id'];
        $this->site_info = M('site')->where($map)->find();
        $this->column_list = M('column')->where($map)->select();
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/article_add.html'); 
    }
    public function article_edit(){
        if($_REQUEST['dosubmit']==1){
            $where['aid'] = trim($_REQUEST['aid']);
            $map['column_id'] = trim($_REQUEST['column_id']);
            $map['title'] = trim($_REQUEST['title']);
            $map['short_title'] = trim($_REQUEST['short_title']);
            $map['author'] = trim($_REQUEST['author']);
            $map['from'] = trim($_REQUEST['from']);
            $map['keywords'] = trim($_REQUEST['keywords']);
            $map['description'] = trim($_REQUEST['description']);
            $map['weight'] = trim($_REQUEST['weight']);
            $map['hits'] = trim($_REQUEST['hits']);

            $a = trim($_REQUEST['isrec'])==1?'a':'';
            $b = trim($_REQUEST['isflash'])==1?'b':'';
            $c = trim($_REQUEST['istop'])==1?'c':'';
            $d = trim($_REQUEST['isbold'])==1?'d':'';
            $map['attributes'] = $a.",".$b.",".$c.",".$d;

            $map['tags'] = trim($_REQUEST['tags']);

            if($_REQUEST['pic']){
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();// 实例化上传类
                $upload->maxSize  = 3145728 ;// 设置附件上传大小
                $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->savePath =  './Public/Uploads/';// 设置附件上传目录
                if(!$upload->upload()) {// 上传错误提示错误信息
                    $this->error($upload->getErrorMsg());
                }else{// 上传成功 获取上传文件信息
                    $info =  $upload->getUploadFileInfo();
                    $map['pic'] = $info[0]['savepath'].$info[0]['savename'];
                }
            }
            $map['comment_open'] = trim($_REQUEST['comment_open']);
            $map['create_time'] = strtotime(trim($_REQUEST['create_time']));
            $map['update_time'] = strtotime(trim($_REQUEST['update_time']));
            $map['content'] = trim($_REQUEST['content']);
            $result = M('article')->where($where)->save($map);
            if($result){
                $this->success('修改成功');
            }else{
                exit(M('article')->getLastSql());
                $this->success('修改失败');
            }
        }
        $map['aid'] = $_GET['aid'];
        $this->info = M('article')->where($map)->find();
        unset($map);
        $map['site_id'] = $this->info['site_id'];
        $this->site_info = M('site')->where($map)->find();
        $this->column_list = M('column')->where($map)->select();
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/article_edit.html'); 
    }
    public function article_delete(){
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/article_delete.html'); 
    }
    public function article_toggle_check(){
        $map['aid'] = trim($_GET['aid']);
        $save['ischeck'] = trim($_GET['ischeck'])==1?0:1;
        $result = M('article')->where($map)->save($save);
        exit(M('article')->getLastSql());
        if($result){
            $this->success("OK");
        }else{
            $this->error("ERROR");
        }
    }

}