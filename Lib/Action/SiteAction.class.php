<?php
/**
* 
*/
class SiteAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }
    public function site_list(){
        $site = M('site');
        $this->site_list = $site->select();
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/site_list.html');
    }
    public function site_add(){
        $str = '';
        $dir = "../Tpl/";
        $preg2 = '/[A-Za-z]/';
        if (is_dir ( $dir )) {
            if ($dh = opendir ( $dir )) {
                while ( ($file = readdir ( $dh )) !== false ) {
                    if (preg_match ( $preg2, $file )) {
                        $str .= '<option value=' . $file . '>' . $file . '</option>';
                    }
                }
                closedir ( $dh );
            }
        }
        $this->assign ( 'template_list', $str );
        if($_REQUEST['dosubmit']==1){
            $map['site_name'] = trim($_REQUEST['site_name']);
            $map['site_title'] = trim($_REQUEST['site_title']);
            $map['domain'] = trim($_REQUEST['domain']);
            $map['keywords'] = trim($_REQUEST['keywords']);
            $map['description'] = trim($_REQUEST['description']);
            $map['url_model'] = trim($_REQUEST['url_model']);
            $map['url_suffix'] = trim($_REQUEST['url_suffix']);
            $map['template'] = trim($_REQUEST['template']);
            $map['sort'] = trim($_REQUEST['sort']);
            $map['isshow'] = trim($_REQUEST['isshow']);
            if(!empty($_FILES["logo"]["name"])){
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();// 实例化上传类
                $upload->maxSize  = 3145728 ;// 设置附件上传大小
                $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->savePath =  $_SERVER['DOCUMENT_ROOT'].'/Public/Uploads/site_logo/';// 设置附件上传目录
                if(!$upload->upload()) {// 上传错误提示错误信息
                    $this->error($upload->getErrorMsg());
                }else{
                    $info = $upload->getUploadFileInfo();//取得成功上传的文件信息
                    $model = M('pic');
                    foreach ($info as $k => $v) {
                        $map['logo'] = '/Public/Uploads/site_logo/'.$info[$k]['savename'];
                    }
                }
            }
            $map['wenww'] = trim($_REQUEST['wenww']);
            $map['icp'] = trim($_REQUEST['icp']);
            $map['beian'] = trim($_REQUEST['beian']);
            $map['statistic_code'] = trim($_REQUEST['statistic_code']);

            $site = M('site');
            if($site->add($map)){
                $this->success('添加成功！');
            }else{
                $this->success('添加失败！');
            }
        }
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/site_add.html');
    }
    public function site_edit(){
        $site = M('site');
        $map['site_id'] = $_GET['site_id'];
        $this->info = $site->where($map)->find();
        unset($map);
        $str = '';
        $dir = "../Tpl/";
        $preg2 = '/[A-Za-z]/';
        if (is_dir ( $dir )) {
            if ($dh = opendir ( $dir )) {
                while ( ($file = readdir ( $dh )) !== false ) {
                    if (preg_match ( $preg2, $file )) {
                        if($this->info['template']==$file){
                            $str .= '<option value=' . $file . ' selected>' . $file . '</option>';
                        }else{
                            $str .= '<option value=' . $file . '>' . $file . '</option>';                           
                        }

                    }
                }
                closedir ( $dh );
            }
        }
        $this->assign ( 'template_list', $str );
        if($_REQUEST['dosubmit']==1){
            $where['site_id'] = trim($_REQUEST['site_id']);
            $map['site_name'] = trim($_REQUEST['site_name']);
            $map['site_title'] = trim($_REQUEST['site_title']);
            $map['domain'] = trim($_REQUEST['domain']);
            $map['keywords'] = trim($_REQUEST['keywords']);
            $map['description'] = trim($_REQUEST['description']);
            $map['url_model'] = trim($_REQUEST['url_model']);
            $map['url_suffix'] = trim($_REQUEST['url_suffix']);
            $map['template'] = trim($_REQUEST['template']);
            $map['sort'] = trim($_REQUEST['sort']);
            $map['isshow'] = trim($_REQUEST['isshow']);
            if(!empty($_FILES["logo"]["name"])){
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();// 实例化上传类
                $upload->maxSize  = 3145728 ;// 设置附件上传大小
                $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->savePath =  $_SERVER['DOCUMENT_ROOT'].'/Public/Uploads/site_logo/';// 设置附件上传目录
                if(!$upload->upload()) {// 上传错误提示错误信息
                    $this->error($upload->getErrorMsg());
                }else{
                    $info = $upload->getUploadFileInfo();//取得成功上传的文件信息
                    $model = M('pic');
                    foreach ($info as $k => $v) {
                        $map['logo'] = '/Public/Uploads/site_logo/'.$info[$k]['savename'];
                    }
                }
            }
            $map['wenww'] = trim($_REQUEST['wenww']);
            $map['icp'] = trim($_REQUEST['icp']);
            $map['beian'] = trim($_REQUEST['beian']);
            $map['statistic_code'] = trim($_REQUEST['statistic_code']);

            $site = M('site');
            if($site->where($where)->save($map)){
                $this->success('修改成功！');
            }else{
                exit($site->getLastSql());
                $this->success('修改失败！');
            }
        }
        $this->display(TMPL_PATH . C("ADMIN_THEME") . '/site_edit.html');
    }
    public function site_delete(){
    }
}