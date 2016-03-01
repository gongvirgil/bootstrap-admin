<?php
/**
* 
*/
class TryAction extends CommonAction
{
    public function index(){
        $this->output(TMPL_PATH . C("ADMIN_THEME") . '/try_index.html');
    }
}