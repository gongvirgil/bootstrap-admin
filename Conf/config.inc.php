<?php
if (! defined ( 'THINK_PATH' ))  exit ('NONE THINK_PATH');
return array(
   	/* 数据库设置 */
	'DB_TYPE'         => 'mysql', // 数据库类型
	'DB_HOST'         => '', // 服务器地址
	'DB_NAME'         => '', // 数据库名
	'DB_USER'         => '', // 用户名
	'DB_PWD'          => '', // 端口
	'DB_PREFIX'       => '', // 数据库表前缀
	'DB_CHARSET'      => 'utf8', // 数据库编码默认采用utf8
	//'DB_DSN'        => 'mysql://root:@localhost:3306/thinkphp'
	'SHOW_PAGE_TRACE' => false, //开启页面Trace
	'ADMIN_THEME'     => 'default',
	'VERSION'         => '1.0 Alpha'
);