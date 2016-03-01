<?php
/**
* 
*/
class MediaAction extends CommonAction
{
    public function __construct(){
        parent::__construct();
        parent::check_user_access(MODULE_NAME, ACTION_NAME);
    }
    public function index(){
        header("Location:".U('Picture/picture_list'));
    }

}