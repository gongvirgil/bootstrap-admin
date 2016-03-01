<?php
/**
* 
*/
class AdminAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }





}