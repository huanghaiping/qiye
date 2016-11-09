<?php
/**
 * 安装程序配置文件
 */
return array (
	'TMPL_ACTION_SUCCESS'=>'Common:success',		//自定义success跳转页面
	'TMPL_ACTION_ERROR'=>'Common:error',		//自定义error跳转页面
	// URL配置
	'URL_CASE_INSENSITIVE' => true, // 默认false 表示URL区分大小写 true则表示不区分大小写
	'URL_MODEL' => 3, // URL模式 使用兼容模式安装
	'ORIGINAL_TABLE_PREFIX' => 'db_'// 默认表前缀
);