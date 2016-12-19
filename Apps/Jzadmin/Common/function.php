<?php
/**
  +----------------------------------------------------------
 * 功能：计算文件大小
  +----------------------------------------------------------
 * @param int $bytes
  +----------------------------------------------------------
 * @return string 转换后的字符串
  +----------------------------------------------------------
 */
function byteFormat($bytes) {
	$sizetext = array (" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB" );
	return round ( $bytes / pow ( 1024, ($i = floor ( log ( $bytes, 1024 ) )) ), 2 ) . $sizetext [$i];
}
/**
  +----------------------------------------------------------
 * 生成随机字符串
  +----------------------------------------------------------
 * @param int       $length  要生成的随机字符串长度
 * @param string    $type    随机码类型：0，数字+大写字母；1，数字；2，小写字母；3，大写字母；4，特殊字符；-1，数字+大小写字母+特殊字符
  +----------------------------------------------------------
 * @return string
  +----------------------------------------------------------
 */
function randCode($length = 5, $type = 0) {
	$arr = array (1 => "0123456789", 2 => "abcdefghijklmnopqrstuvwxyz", 3 => "ABCDEFGHIJKLMNOPQRSTUVWXYZ", 4 => "~@#$%^&*(){}[]|" );
	if ($type == 0) {
		array_pop ( $arr );
		$string = implode ( "", $arr );
	} else if ($type == "-1") {
		$string = implode ( "", $arr );
	} else {
		$string = $arr [$type];
	}
	$count = strlen ( $string ) - 1;
	$code = "";
	for($i = 0; $i < $length; $i ++) {
		$str [$i] = $string [rand ( 0, $count )];
		$code .= $str [$i];
	}
	return $code;
}
/**
 * 获取地区的名称
 * Enter description here ...
 */
function getAreasName($parent_id) {
	if (empty ( $parent_id ))
		return false;
	$area = D ( "Area" );
	$result = $area->getAreaName ( array ('province' => $parent_id ) );
	return $result ? $result [0] : "";
}

/**
 * 判断文章的属性
 * Enter description here ...
 * @param unknown_type $flag
 */
function getColorFlag($flag) {
	$str = "";
	if ((! strpos ( $flag, "," )) && ! is_array ( $flag )) {
		$str = getFlagStr ( $flag );
	} else {
		$str_arr = explode ( ",", $flag );
		foreach ( $str_arr as $value ) {
			$str .= getFlagStr ( $value );
		}
	}
	return ! empty ( $str ) ? $str : false;

}

/**
 * 获取属性名称
 * Enter description here ...
 * @param unknown_type $flag
 */
function getFlagStr($flag) {
	$lang_list = F ( "RECOMEND_LIST" );
	$position_info=array();
	if (! $lang_list) {
		$posid_model = D ( "Posid" );
		$position_info = $posid_model->where ( "id='" . $flag . "'" )->field ( "id,name" )->find ();
	} else {
		foreach ( $lang_list as $value ) {
			if ($value ['id'] == $flag) {
				$position_info = $value;
				break;
			}
		}
	}
	if (!empty($position_info)){
		$str = "<span style='color:#F00;font-size:12px;' title='" . $position_info ['name'] . "'>[" . $position_info ['name'] . "]</span>";
	}
	return $str;
}

/**
 * 删除资讯内容里图片和附件
 * @param 	string	$content 需要删除的内容
 *+---------------------------------------------
 *@return Null
 */
function delContentImg($content) {
	preg_match_all ( '/<img.*?src=\s*?"?([^"\s]+)(?!\/>)"?\s*?/is', $content, $img_array );
	if ($img_array) {
		foreach ( $img_array [1] as $cc => $image ) {
			if (preg_match ( '/^\/Uploads/i', $image )) {
				@unlink ( "./" . $image ); //删除图片
			}
		}
	}
	preg_match_all ( '/<a.*?href=\s*?"?([^"\s]+)(?!\/>)"?\s*?/is', $content, $link_array );
	if ($link_array) {
		foreach ( $link_array [1] as $cc => $link ) {
			if (preg_match ( '/^\/Uploads/i', $link )) {
				@unlink ( "./" . $link ); //删除附件
			}
		}
	}
}

/**
  +-----------------------------------------------------------------------------------------
 * 删除目录及目录下所有文件或删除指定文件
  +-----------------------------------------------------------------------------------------
 * @param str $path   待删除目录路径
 * @param int $delDir 是否删除目录，1或true删除目录，0或false则只删除文件保留目录（包含子目录）
  +-----------------------------------------------------------------------------------------
 * @return bool 返回删除状态
  +-----------------------------------------------------------------------------------------
 */
function delDirAndFile($path, $delDir = FALSE) {
	$handle = opendir ( $path );
	if ($handle) {
		while ( false !== ($item = readdir ( $handle )) ) {
			if ($item != "." && $item != "..")
				is_dir ( "$path/$item" ) ? delDirAndFile ( "$path/$item", $delDir ) : unlink ( "$path/$item" );
		}
		closedir ( $handle );
		if ($delDir)
			return rmdir ( $path );
	} else {
		if (file_exists ( $path )) {
			return unlink ( $path );
		} else {
			return FALSE;
		}
	}
}
/**
  +----------------------------------------------------------
 * 功能：检测一个目录是否存在，不存在则创建它
  +----------------------------------------------------------
 * @param string    $path      待检测的目录
  +----------------------------------------------------------
 * @return boolean
  +----------------------------------------------------------
 */
function makeDir($path) {
	return is_dir ( $path ) or (makeDir ( dirname ( $path ) ) and @mkdir ( $path, 0777 ));
}

/**
 * 创建菜单的访问url
 * Enter description here ...
 * @param unknown_type $v
 */
function createAdminUrl($v) {
	$jump = "";
	if (empty ( $v ['module'] )) {
		$jump = U ( $v ['name'] . "/index" );
	} else {
		if (strpos ( $v ['module'], "/" ) === false) {
			$jump = U ( $v ['name'] . "/" . $v ['module'] );
		} else {
			$pos = explode ( "/", $v ['module'] );
			$a = $pos [0];
			unset ( $pos [0] );
			$param = array ();
			foreach ( $pos as $key => $value ) {
				if ($key % 2 == 0) {
					$param [$pos [$key - 1]] = $value;
				}
			}
			$jump = U ( $v ['name'] . "/" . $a, $param );
		}
	}
	return $jump;
}


/**
 * 
 * 获取模型对应的模板
 * +-----------------------------------------------------------
 * @param String $module	模型名称
 * @param String $path		模板路径
 * @param String $ext		模板的后缀名
 * 
 */
function template_file($module='',$path='',$ext='html'){
	$theme_path=D('Site')->getConfirByKey('DEFAULT_THEME');
	$theme_path= $theme_path ?  $theme_path : "default";
	$path= $path ? $path : APP_PATH.'/'.'Home/'.C('DEFAULT_V_LAYER').'/'.$theme_path.'/'.$module."/";
	$tempfiles = dir_list($path,$ext);
	foreach ($tempfiles as $key=>$file){
		$dirname = basename($file);
		$arr[$key]['name'] = substr($dirname,0,strrpos($dirname, '.'));
		$arr[$key]['value'] =  $module.":".substr($dirname,0,strrpos($dirname, '.'));
		$arr[$key]['filename'] = $dirname;
		$arr[$key]['filepath'] = $file;
	}
	return  $arr;
}

/**
 * 
 * 获取文件的后缀名
 * +-------------------------------------------------------------
 * @param String $filename		文件名
 * @return String
 */
function fileext($filename) {
	return strtolower(trim(substr(strrchr($filename, '.'), 1, 10)));
}

/**
 * 
 * 对转义路径进行转义
 * +-------------------------------------------------------------
 * @param unknown_type $path
 */
function dir_path($path) {
	$path = str_replace('\\', '/', $path);
	if(substr($path, -1) != '/') $path = $path.'/';
	return $path;
}
/**
 * 
 * 遍历文件
 * +-------------------------------------------------------------
 * @param string $path	模板路径
 * @param string $exts  模板的后缀
 * @param Array  $list  模板后缀
 */
function dir_list($path, $exts = '', $list= array()) {
	$path = dir_path($path);
	$files = glob($path.'*');
	foreach($files as $v) {
		$fileext = fileext($v);
		if (!$exts || preg_match("/\.($exts)/i", $v)) {
			$list[] = $v;
			if (is_dir($v)) {
				$list = dir_list($v, $exts, $list);
			}
		}
	}
	return $list;
}

/**
 * 
 * 在文件扩展名的前加* 号
 * +----------------------------------------------------------
 * @param string $ext_string
 */
function getImageExt($ext_string){
	if (empty($ext_string))  return false;
	$ext_array=explode(",", $ext_string);
	foreach ($ext_array as $key=>$value){
		$ext_array[$key]="*.".$value;
	}
	return implode(";", $ext_array);
}

/**
 * 显示菜单的位置列表
 * 
 *  @param int  $value 位置列表的值
 *  @return Array
 */
function showPosition($value=""){
	$position = array (0=>'其它',1=>'顶部',2=>'导航',3=>'底部');
	return (string)$value=="" ? $position : $position [$value];
}
