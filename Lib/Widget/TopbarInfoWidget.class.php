<?php
class TopbarInfoWidget extends Widget{
    public function render($data){
        $sites = explode(",", $_SESSION['access']);
        if(!$_COOKIE['site_id']) SETCOOKIE('site_id',$sites[0]);
        $map['site_id'] = array('in',$sites);
        $data['site_list'] = M('site')->where($map)->order('site_id desc')->select();
        $content = $this->renderFile(TMPL_PATH . C("ADMIN_THEME") . '/topbar-info.html',$data);
        return $content;
    }
}