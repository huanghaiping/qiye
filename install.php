<?php
if (version_compare ( PHP_VERSION, '5.3.0', '<' ))
	die ( 'Your PHP Version is ' . PHP_VERSION . ', but WeiPHP require PHP > 5.3.0 !' );
$_GET ['m'] = 'Install';
/**
 * 此处APP_DEBUG一定要设置为true，否则安装后会生成错误的缓存文件
 */
define ( 'APP_DEBUG', true );
define ( 'BIND_MODULE', 'Install' );
/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define ( 'APP_PATH', './Apps/' );
define ( "UPLOAD_PATH", './Uploads/' );
define ( "INCLUDE_PATH", './Include/' );
define("ADMIN_NAME", 'Jzadmin');//绑定后台的项目名称
if (is_file ( INCLUDE_PATH . '/install.lock' )) { //判断是否安装目录
	header ( 'Location: /index.php' );
	exit ();
}
/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ( 'RUNTIME_PATH', './Runtime/' );
// 引入ThinkPHP入口文件
require './Core/ThinkPHP.php';