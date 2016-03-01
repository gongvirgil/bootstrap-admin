<?php
class RoleAccessViewModel extends ViewModel {
    public $viewFields = array(
        'admin_menu'=>array('menu_id','menu_name','menu_method','isshow','father_id','sort','description'),
        'admin_role_access'=>array('role_id','menu_id','_on'=>'admin_role_access.menu_id=admin_menu.menu_id')
    );
}