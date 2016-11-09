<?php
/**
 * +-----------------------------------
 * 手机号码替换为*号
 * +-----------------------------------
 * @param string $mobile
 */
function mobile_replace($mobile) {
	return substr_replace ( $mobile, '****', 3, 4 );
}

/**
 * +-----------------------------------------------
 * 判断是在微信客户端(JSAPI)还是在pc端(NATIVE)
 * Enter description here ...
 */
function is_weixin() {
	if (strpos ( $_SERVER ['HTTP_USER_AGENT'], 'MicroMessenger' ) !== false) {
		return true;
	}
	return false;
}
/**
 * +-----------------------------------------------
 * 判断是在手机端还是pc 端
 * Enter description here ...
 */
function is_mobile_request() {
	$_SERVER ['ALL_HTTP'] = isset ( $_SERVER ['ALL_HTTP'] ) ? $_SERVER ['ALL_HTTP'] : '';
	$mobile_browser = '0';
	if (preg_match ( '/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower ( $_SERVER ['HTTP_USER_AGENT'] ) ))
		$mobile_browser ++;
	if ((isset ( $_SERVER ['HTTP_ACCEPT'] )) and (strpos ( strtolower ( $_SERVER ['HTTP_ACCEPT'] ), 'application/vnd.wap.xhtml+xml' ) !== false))
		$mobile_browser ++;
	if (isset ( $_SERVER ['HTTP_X_WAP_PROFILE'] ))
		$mobile_browser ++;
	if (isset ( $_SERVER ['HTTP_PROFILE'] ))
		$mobile_browser ++;
	$mobile_ua = strtolower ( substr ( $_SERVER ['HTTP_USER_AGENT'], 0, 4 ) );
	$mobile_agents = array ('w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac', 'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno', 'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-', 'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-', 'newt', 'noki', 'oper', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox', 'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar', 'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-', 'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp', 'wapr', 'webc', 'winw', 'winw', 'xda', 'xda-' );
	if (in_array ( $mobile_ua, $mobile_agents ))
		$mobile_browser ++;
	if (strpos ( strtolower ( $_SERVER ['ALL_HTTP'] ), 'operamini' ) !== false)
		$mobile_browser ++;
	
		// Pre-final check to reset everything if the user is on Windows  
	if (strpos ( strtolower ( $_SERVER ['HTTP_USER_AGENT'] ), 'windows' ) !== false)
		$mobile_browser = 0;
	
		// But WP7 is also Windows, with a slightly different characteristic  
	if (strpos ( strtolower ( $_SERVER ['HTTP_USER_AGENT'] ), 'windows phone' ) !== false)
		$mobile_browser ++;
	if ($mobile_browser > 0)
		return true;
	else
		return false;
}

/**
 * +-----------------------------------------------
 * 客服系统的解析
 * Enter description here ...
 */
function kfInfo($pay_config) {
	if (empty ( $pay_config ))
		return false;
	$pay_config = unserialize ( $pay_config );
	if ($pay_config) {
		$model_name = D ( ADMIN_NAME . "/Kefu" );
		foreach ( $pay_config as $key => $value ) {
			$value ['thumb_info'] = $model_name->getSkin ( $value ['key'] );
			$pay_config [$key] = $value;
		}
	}
	return $pay_config;
}
/**
 * +-----------------------------------------------
 * 客服系统的类型代码
 * +-----------------------------------------------
 * 
 * @param int $typeid	分类ID
 * @param int $skinId	皮肤ID
 * @param int $number	号码
 */
function kfType($typeid, $skinId, $number) {
	if (empty ( $typeid ))
		return false;
	$code_string = "";
	switch ($typeid) {
		case 1 :
			$code_string = '<a target=blank href=tencent://message/?uin=' . $number . '&Menu=yes><img border="0" SRC=http://wpa.qq.com/pa?p=1:' . $number . ':' . $skinId . ' ></a>';
			break;
		case 2 :
			$model_name = D ( ADMIN_NAME . "/Kefu" );
			$thumb_info = $model_name->getSkin ( $skinId );
			$code_string = '<a href="msnim:chat?contact=' . $number . '"><img src="'.__ROOT__.'/Public/images/kefu/'.$thumb_info['thumb'].'" style="border: none;" alt="Call me!" /></a>';
			break;
			break; //MSN
		case 3 :
			$code_string = '<a target="_blank" href="http://amos1.taobao.com/msg.ww?v=2&uid=' . $number . '=1" ><img border="0" src="http://amos1.taobao.com/online.ww?v=2&uid=' . $number . '=1" /></a>';
			break; //旺旺 
		case 4 :
			$code_string = '<a href="tel:'.$number.'">'.$number.'</a>';
			break; //电话
		case 7 :
			$model_name = D ( ADMIN_NAME . "/Kefu" );
			$thumb_info = $model_name->getSkin ( $skinId );
			$code_string = '<a href="skype:' . $number . '?add"><img src="'.__ROOT__.'/Public/images/kefu/'.$thumb_info['thumb'].'" style="border: none;" alt="Call me!" /></a>';
			break; //skype
	}
	return $code_string;
}

/**
 * +-----------------------------------------------
 * 解析内容多图
 * +-----------------------------------------------
 * 
 * @param string $images	 多图字符串
 * @param string $separator	 分隔符
 */
function slide($images,$separator=":::"){
	if(empty($images)) return array();
	if(strpos($images,$separator)){
		$thumbs=explode($separator,$images);
		if($thumbs){
			$row=array();
			foreach($thumbs as $value){
				if(strpos($value,"|")){
					$image=explode("|",$value);
					$row[]=array('thumb'=>$image[0],"title"=>$image[1]);
				}else{
					$row[]=$value;
				}
			}
			return $row;
		}else{
			return array();	
		}
	}else{
		 return array();
	}
}