<?php
/**
 * 检查email的格式是否正确
 * @param string $email 需要判断的邮箱
 * @return booltrue
 */
function checkEmailFormat($email) {
	$pregEmail = "/([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?/i";
	if (preg_match ( $pregEmail, $email )) {
		return true;
	} else {
		return false;
	}
}
/**
 * 验证手机号码
 * Enter description here ...
 * @param unknown_type $tel
 */
function valdeTel($tel) {
	$pattern = "/^13[0-9]{9}$|17[0-9]{9}$|15[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|14[0-9]{1}[0-9]{8}$/i";
	if (! preg_match ( $pattern, $tel )) {
		return false;
	}
	return true;
}

/**
 * 字符串截取，支持中文和其他编码
 * Enter description here ...
 * @param unknown_type $str
 * @param unknown_type $start
 * @param unknown_type $length
 * @param unknown_type $charset
 * @param unknown_type $suffix
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = false) {
	if (strpos ( $str, "<img" ) !== false)
		return $str;
	if (function_exists ( "mb_substr" ))
		$slice = mb_substr ( $str, $start, $length, $charset );
	elseif (function_exists ( 'iconv_substr' )) {
		$slice = iconv_substr ( $str, $start, $length, $charset );
	} else {
		$re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all ( $re [$charset], $str, $match );
		$slice = join ( "", array_slice ( $match [0], $start, $length ) );
	}
	return $suffix ? $slice . '...' : $slice;
}

/**
 * 全站静态缓存,替换之前每个model类中使用的静态缓存,类似于s和f函数的使用
 * Enter description here ...
 * @param unknown_type $cache_id
 * @param unknown_type $value
 * @param unknown_type $clean
 */
function static_cache($cache_id, $value = null, $clean = false) {
	static $cacheHash = array ();
	if ($clean) { //清空缓存 其实是清不了的 程序执行结束才会自动清理
		unset ( $cacheHash );
		$cacheHash = array (0 );
		return $cacheHash;
	}
	if (empty ( $cache_id )) {
		return false;
	}
	if ($value === null) {
		//获取缓存数据
		return isset ( $cacheHash [$cache_id] ) ? $cacheHash [$cache_id] : false;
	} else {
		//设置缓存数据
		$cacheHash [$cache_id] = $value;
		return $cacheHash [$cache_id];
	}
}

/**
 * 平台切换
 * Enter description here ...
 * @param unknown_type $type
 */
function getPlatform($type) {
	$type_str = "";
	if (is_numeric ( $type )) {
		switch ($type) {
			case 0 :
				$type_str = "web";
				break;
			case 1 :
				$type_str = "QQ";
				break;
			case 2 :
				$type_str = "新浪";
				break;
			case 3 :
				$type_str = "微信";
				break;
		}
	} else {
		switch ($type) {
			case "web" :
				$type_str = 0;
				break;
			case "QQ" :
				$type_str = 1;
				break;
			case "SINA" :
				$type_str = 2;
				break;
			case "WeiXin" :
				$type_str = 3;
				break;
		}
	}
	return $type_str;
}

/**
 * 远程抓取内容
 * return
 * @param unknown_type $url
 */
function get_url_content($url) {
	set_time_limit ( 0 );
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
	$result = curl_exec ( $ch );
	curl_close ( $ch );
	return $result;
}

/**
 * 取一个二维数组中的每个数组的固定的键知道的值来形成一个新的一维数组
 * @param $pArray 一个二维数组
 * @param $pKey 数组的键的名称
 * 
 * @return 返回新的一维数组
 */
function getSubByKey($pArray, $pKey = "", $pCondition = "") {
	$result = array ();
	if (is_array ( $pArray )) {
		foreach ( $pArray as $temp_array ) {
			if (is_object ( $temp_array )) {
				$temp_array = ( array ) $temp_array;
			}
			if (("" != $pCondition && $temp_array [$pCondition [0]] == $pCondition [1]) || "" == $pCondition) {
				$result [] = ("" == $pKey) ? $temp_array : isset ( $temp_array [$pKey] ) ? $temp_array [$pKey] : "";
			}
		}
		return $result;
	} else {
		return false;
	}
}

/**
 * 获取性别名称
 * Enter description here ...
 */
function getSex($sex_id) {
	$sex_str = "";
	switch ($sex_id) {
		case 0 :
			$sex_str = "保密";
			break;
		case 1 :
			$sex_str = "男";
			break;
		case 2 :
			$sex_str = "女";
			break;
		default :
			$sex_str = "保密";
			break;
	}
	return $sex_str;
}

/**
 * 获取文章的属性
 * 
 * @param string $str	所有的选项
 * @param string $flag  内容值
 * 
 * @return bool 返回真假
 */
function getFlag($str, $flag) {
	if ((! strpos ( $str, "," )) && ! is_array ( $str )) {
		return $str == $flag ? true : false;
	} else {
		$str_arr = explode ( ",", $str );
		return in_array ( $flag, $str_arr ) ? true : false;
	}
}

/**
 * 根据时间戳获取不同语言时间格式
 * 
 * @param sring $time	时间戳
 * @return string time_str	返回带格式的的时间
 */
function getTimeInfo($time){
	if (empty($time)) $time=time();
	$time_str="";
	switch (LANG_SET){
		case "cn" :  $time_str=date("Y-m-d H:i:s",$time); break;
		case "en" :  $time_str=date("Y-m-d H:i:s",$time); break;
	}
	return $time_str;
}

/**
 * 
 * 数组转换为字符串 
 * 
 * @param Array $info
 * @return String 返回字符串
 */
function array2serialize($info) {  
	if($info == '') return '';
	return serialize($info);
}

/**
 * 
 * 数组转换为字符串 
 * 
 * @param Array $info
 * @return String 返回字符串
 */
function serialize2array($info) {
        if($info == '') return array();
        if (!get_magic_quotes_gpc()) {
        	 return unserialize(htmlspecialchars_decode($info));
        }else{
        	 return unserialize( htmlspecialchars_decode ( $info ) );
	}

}

/**
     +----------------------------------------------------------
 * 如果 magic_quotes_gpc 为关闭状态，这个函数可以转义字符串
     +----------------------------------------------------------
 * @access public
     +----------------------------------------------------------
 * @param string $string 要处理的字符串
     +----------------------------------------------------------
 * @return string
     +----------------------------------------------------------
 */
function addSlashesFun($string) {
	if (! get_magic_quotes_gpc ()) {
		$string = addslashes ( $string );
	}
	return $string;
}
/**
 * 
 * 创建表单模型
 * @param object $form 表单模型对象
 * @param Array $info	字段的信息
 * @param string $value 对应模型的值
 */
function getform($form, $info, $value = '') {
	return $form->$info ['type'] ( $info, $value );
}

/**
 * 
 * 格式化前端表达验证
 * +--------------------------------------------------------------------
 * @param Array $info
 * @return String $parseStr
 */
function getvalidate($info) {
	$validate_data = array ();
	if ($info ['minlength'])
		$validate_data ['minlength'] = ' minlength:' . $info ['minlength'];
	if ($info ['maxlength']) {
		$validate_data ['maxlength'] = ' maxlength:' . $info ['maxlength'];
	}
	if ($info ['required'])
		$validate_data ['required'] = ' required:true ';
	if($info['pattern']) $validate_data['pattern'] = ' '.$info['pattern'].':true';
    if($info['errormsg']) $errormsg = ' title="'.$info['errormsg'].'"';
	$validate = implode ( ',', $validate_data );
	$validate = $validate ? 'validate="' . $validate . '" ' : '';
	$parseStr = $validate;
	return $parseStr;
}


/**
 * 
 * 字符串url转为数组
 * @param String $str
 */
function string2array($str, $space = "/") {
	$pos = explode ( $space, $str );
	$a = $pos [0];
	unset ( $pos [0] );
	$param = array ();
	foreach ( $pos as $key => $value ) {
		if ($key % 2 == 0) {
			$param [$pos [$key - 1]] = $value;
		}
	}
	return $param;
}

/**
 * 创建前台的URL
 * 
 * @param Array $cat
 * @param Array $data
 * @param Array $Urlrule
 */
function createHomeUrl($cat, $id = '', $param = array()) {
	if ($cat ['typeid'] == 0 && empty ( $id )) {
		return $cat ['param'];
	}
	if (!empty($id)&&isset($param['jump_url'])){
		return $param['jump_url'];
	}
	$URL_MODEL = C ( 'URL_MODEL' );
	$var_page = C ( 'VAR_PAGE' );
	$catmodule = $cat ['model_name'];
	$catdir = $cat ['route'];
	$catid = $cat ['id'];
	$urls = "";
	if ($cat ['listtype'] == 1) {
		$listtype = 'index';
		$space = "-";
	} else {
		$listtype = 'lists';
		$space = "_";
	}
	 
	$page = isset ( $param [$var_page] ) && ! empty ( $param [$var_page] ) ? 1 : 0; //判断是否带有分页参数
	if ($URL_MODEL !=2 || empty ( $catdir )) { //普通模式
		if ($id) {
			if (empty ( $param ) && $URL_MODEL == 2) {
				$page_code = urlencode ( $param [$var_page] );
				$url = $page ? U ( '/' . strtolower ( $catmodule ) . "/" . $id . "/" . $page_code . '/' ) : U ( '/' . strtolower ( $catmodule ) . "/" . $id . '/' );
				$url = str_replace ( strtolower ( $page_code ), $page_code, $url );
			} else {
				$param = array_merge ( $param, array ('id' => $id ) );
				$url = U ( $catmodule . "/detail", $param );
			}
		} else {
			if ($page && empty ( $cat ['param'] ) && $URL_MODEL == 2) { 
				$page_code = urlencode ( $param [$var_page] );
				$url = U ( '/' . strtolower ( $catmodule ) . $space . $catid . $space . $page_code . '/' );
				$url = str_replace ( strtolower ( $page_code ), $page_code, $url );
			} else {
				if (empty ( $param ) && empty ( $cat ['param'] ) && $URL_MODEL == 2) {
					$url = U ( '/' . strtolower ( $catmodule ) . $space . $catid . '/' );
				} else {
					$param = array_merge ( $param, array ('id' => $catid ) );
					if (! empty ( $cat ['param'] )) {
						$param = array_merge ( $param, string2array ( $cat ['param'] ) );
					}
					$url = U ( $catmodule . "/" . $listtype, $param );
				}
			}
		}
		 
		$urls = str_replace (array('m=&','m='.strtolower(ADMIN_NAME).'&','m='.ADMIN_NAME.'&'), array('','',''), $url );
	
	} else { // PATHINFO模式或者兼容URL模式
		$showurlrule = '{$catdir}/{$id}.html|{$catdir}/{$id}_{$page}.html';
		$listurlrule = '{$catdir}/|{$catdir}_{$catid}_{$page}.html';
		$index = $URL_MODEL == 1 ? __ROOT__ . '/index.php/' : __ROOT__ . '/';
		$search_array = array ('{$catdir}', '{$catid}', '{$id}', '{$page}' );
		$replace_array = array ($catdir, $catid, $id, urlencode ( $param [$var_page] ) );
		if ($id) {
			$urls = str_replace ( $search_array, $replace_array, $showurlrule );
		} else {
			$urls = str_replace ( $search_array, $replace_array, $listurlrule );
		}
		$urls = explode ( '|', $urls );
		if ($page) {
			$urls = $index . $urls [1];
		} else {
			$urls = $index . $urls [0];
		}
	}
	return $urls;
}

/**
 * +---------------------------------------
 * 判断url是否有效
 * +---------------------------------------
 * @param string	 $url
 */
function valid_host($url) {
	$url = str_replace ( array ('https://', 'http://' ), '', $url );
	$url = trim ( $url, '/' );
	if (preg_match ( "/^([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i", $url )) {
		return $url;
	}
	return false;
}

