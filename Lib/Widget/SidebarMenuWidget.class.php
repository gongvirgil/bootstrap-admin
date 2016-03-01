<?php
class SidebarMenuWidget extends Widget {
    public function render($data){    
        import('./Action/CommonAction.class.php');
        $data['menu_list'] = CommonAction::show_menu();
        $content = $this->renderFile(TMPL_PATH . C("ADMIN_THEME") . '/sidebar-menu.html',$data);
        return $content;
    }
}