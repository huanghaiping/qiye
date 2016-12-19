<?php
$lang_list = F ( 'Lang' ,'',INCLUDE_PATH);
if ($lang_list) $lang_array=implode(",", array_keys($lang_list));
return array(
		// 数据库配置
        'DB_TYPE'   => 'mysql', // 数据库类型
        'DB_HOST'   => '127.0.0.1', // 服务器地址
        'DB_NAME'   => 'songying', // 数据库名
        'DB_USER'   => 'root', // 用户名
        'DB_PWD'    => '',  // 密码
        'DB_PORT'   => '3306', // 端口
        'DB_PREFIX' => 'sy_', // 数据库表前缀

	'URL_MODEL'=>0,
	'DEFAULT_MODULE'       =>    'Home',
	'MODULE_ALLOW_LIST' => array('Home','Jzadmin'),

	/*语言设置*/
	"LANG_SWITCH_ON"=>true,					 // 开启语言包功能
	'LANG_AUTO_DETECT' => true, // 自动侦测语言 开启多语言功能后有效
	'LANG_LIST'        => $lang_array, // 允许切换的语言列表 用逗号分隔,ge 德语
	'VAR_LANGUAGE'     => 'l', // 默认语言切换变量

	'TMPL_ACTION_SUCCESS'=>'Common:success',		//自定义success跳转页面
	'TMPL_ACTION_ERROR'=>'Common:error',		//自定义error跳转页面

	/* 数据缓存设置 */
	'UPLOAD_ROOT_PATH'=>UPLOAD_PATH,
	'UPLOAD_IMG_SIZE' => 2097152, //上传的图片大小
	'ADMIN_SITE_URL'=>"http://b.eqiseo.cn",
	'SOFT_NAME'=>'EqiSeo_V1.0_20160808',
	'SOFT_VERSION'=>'V1.0',
	'SOFT_ID'=>'100000',

);