<?php
class ManagerViewModel extends ViewModel{
    public $viewFields = array(
        'admin_role'=>array('role_id','role_name','description','enable'),
        'admin_manager'=>array('manager_id','manager_name','manager_pwd','manager_email','login_ip','login_os','login_time','login_count','status','access','_on'=>'admin_role.role_id=admin_manager.role_id')
    );
}