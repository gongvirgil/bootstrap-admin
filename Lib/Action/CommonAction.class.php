<?php
class CommonAction extends Action{
	public function _initialize() {
		$map ['ip'] = get_client_ip ();
		$model = M ( 'web_not_allow_ip' );
		$st = $model->where ( $map )->find ();unset($map);
		if ($st) {
			header ( 'HTTP/1.1 404 Not Found' );
			header ( "status: 404 Not Found" );
			exit ();
		}
		import ( '@.ORG.Util.Cookie' );
		// 用户权限检查
		if (C ( 'USER_AUTH_ON' ) && ! in_array ( MODULE_NAME, explode ( ',', C ( 'NOT_AUTH_MODULE' ) ) )) {
			import ( '@.ORG.Util.RBAC' );
			//var_dump($_SESSION);exit();
			if (! $_SESSION ['user']) {
				redirect ( U ( 'Public/login' ) );
				unset ( $_SESSION ['user'] );
			}
		}
		//当前模块
		$this->menu = $this->get_one_menu(ACTION_NAME);
		$this->menu_chain = array_reverse($this->get_menu_chain($this->menu,true));
		//exit(json_encode($this->menu_chain ));
	}
    public function get_menu_list(){
        $menu = M('admin_menu');
        $map['father_id'] = 0;
        $menu_list = $menu->where($map)->order("sort asc")->select();
        unset($map);
        foreach ($menu_list as $k => $v) {
            $map['father_id'] = $v['menu_id'];
            $menu_list[$k]['son'] = $menu->where($map)->order("sort asc")->select();
            unset($map);
            foreach ($menu_list[$k]['son'] as $k1 => $v1) {
                $map['father_id'] = $v1['menu_id'];
                $menu_list[$k]['son'][$k1]['son'] = $menu->where($map)->order("sort asc")->select();
            }
            unset($map);
        }
        return $menu_list; 
    }
	public function get_one_menu($method){
		$menu_method = $map['menu_method'] = $method;
		$menu_name = M('admin_menu')->where($map)->getField('menu_name');
		$father_id = M('admin_menu')->where($map)->getField('father_id');
		unset($map);
		switch ($father_id) {
			case 0:
				$menu_name = "首页";
				$menu_link = C('ADMIN_PATH');
				$father_method = null;
				break;
			case (1||2):
				$map['menu_id'] = $father_id;
				$father_method = M('admin_menu')->where($map)->getField('menu_method');
				$menu_link = U($menu_method."/index");
				break;		
			default:
				$map['menu_id'] = $father_id;
				$father_method = M('admin_menu')->where($map)->getField('menu_method');
				$menu_link = U($father_method."/".$menu_method);
				break;
		}
		return array(
			'menu_method'   => $menu_method,
			'menu_name'     => $menu_name,
			'menu_link'     => $menu_link,
			'father_id'     => $father_id,
			'father_method' => $father_method,
		);
	}
	public function get_menu_chain($arr,$isFirst=false){
		if($isFirst){$arrnew = $arr;unset($arr);$arr[0] = $arrnew;}
		$arr0 = $arr[count($arr)-1];
		if($arr0['father_id']==0){return $arr;}
		$arr[count($arr)] = $this->get_one_menu($arr0['father_method']);
		return $this->get_menu_chain($arr);
	}
	public function check_user_access($module,$action){
		$map['menu_method'] = $action;
		$id = M('admin_menu')->where($map)->getField('menu_id');
		unset($map);
		$map['role_id'] = $_SESSION ['role_id'];
		$map['menu_id'] = $id;
		$result = M('admin_role_access')->where($map)->select();
		//exit(M('admin_role_access')->_sql());
		if(!$result){
			$this->error ( $id.'未授权,非法访问!');
		}
	}
	public function show_menu(){
		$manager = M ( 'admin_manager' );
		$map['manager_name'] = $_SESSION['user'];
		$role_id = $manager->where($map)->getField( 'role_id' );
		unset($map);
		$access = D ( 'RoleAccessView' );
		$map['admin_role_access.role_id'] = $role_id;
		$map['admin_menu.isshow']         = 1;
		$map['admin_menu.father_id']      = 0;
		$menu_list = $access->where ($map)->order ( 'admin_menu.sort asc' )->select ();
        foreach ($menu_list as $k => $v) {
        	$map['admin_menu.father_id'] = $v['menu_id'];
            $menu_list[$k]['son'] = $access->where($map)->order ( 'admin_menu.sort asc' )->select ();
            foreach ($menu_list[$k]['son'] as $k1 => $v1) {
                $map['admin_menu.father_id'] = $v1['menu_id'];
                $menu_list[$k]['son'][$k1]['son'] = $access->where($map)->order("admin_menu.sort asc")->select();
            }
        }
		return $menu_list;	
	}
	public function get_manager_info($manager,$field="*"){
		$model = M('admin_manager as a,'.C('DB_basedata')["db_prefix"].'admin_role as b');
		$map['manager_name'] = $manager;
		$map['_string'] = "a.role_id = b.role_id";
		$manager_info = $model->where($map)->field($field)->find();
		return $manager_info;
		
	}
	public function output($teplate_file){
        $content1 = $this->fetch(TMPL_PATH . C("ADMIN_THEME") . '/index.html');
        $content2 = $this->fetch($teplate_file);
        $content = str_replace("###RIGHT-INDEX###", $content2, $content1);
        $this->show($content);
	}
}
?>