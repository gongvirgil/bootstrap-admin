<?php
/**
* 
*/
class WebAction extends CommonAction
{
    
    public function __construct(){
        parent::__construct();
        //parent::check_user_access(MODULE_NAME,ACTION_NAME);
    }
    public function index(){
        header("location:".U('web/site_list'));
    }
}