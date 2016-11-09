<?php
$system_config=F("SysConfig",'',INCLUDE_PATH);
$url_model=array('URL_MODEL'=>!empty($system_config)&&isset($system_config['URL_MODEL']) ? $system_config['URL_MODEL'] :0);
$config= array(
	//RBAC的验证权限
	'USER_AUTH_ON'=>true,				// 是否需要认证
	'USER_AUTH_MODEL'=>'Admin',			// 默认验证数据表模型
	'USER_AUTH_TYPE'=>1,				//  默认认证类型 1 登录认证 2 实时认证
	'USER_AUTH_KEY'=>'qiye_authId',	//  认证识别号
	'AUTH_PWD_ENCODER'=>'md5',			//   用户认证
	'REQUIRE_AUTH_MODULE'=>'',			//   需要认证模块
	'NOT_AUTH_MODULE'=>'Login,Index',				//  无需认证模块
	'ADMIN_ROLE_ID'=>1, //超级管理员用户组的id，默认是1
    'RBAC_ROLE_TABLE' => 'admin_role',
    'RBAC_USER_TABLE' => 'admin',
    'RBAC_ACCESS_TABLE' => 'admin_access',
    'RBAC_NODE_TABLE' => 'admin_node',
	
	 /*
     * 系统备份数据库时每个sql分卷大小，单位字节
     */
	'DatabaseBackDir'=>INCLUDE_PATH.'backup',
    'sqlFileSize' => 5242880, //该值不可太大，否则会导致内存溢出备份、恢复失败，合理大小在512K~10M间，建议5M一卷
);
return array_merge($config, $url_model );