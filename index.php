<?php
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',false);

// 定义应用目录
//define('BIND_MODULE', 'Admin'); 
define('APP_PATH','./Apps/');
define("UPLOAD_PATH", './Uploads/');
define("INCLUDE_PATH", './Include/');
define("ADMIN_NAME", 'Jzadmin');//绑定后台的项目名称
if (! is_file ( INCLUDE_PATH.'/install.lock' )) { //判断是否安装目录
	header ( 'Location: /install.php' );
	exit ();
}
/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ( 'RUNTIME_PATH', './Runtime/' );
// 引入ThinkPHP入口文件
require './Core/ThinkPHP.php';