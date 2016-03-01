<?php
class FooterbarInfoWidget extends widget
{
    public function render($data){
        $content = $this->renderFile(TMPL_PATH . C("ADMIN_THEME") . '/footerbar-info.html',$data);
        return $content;
    }
}