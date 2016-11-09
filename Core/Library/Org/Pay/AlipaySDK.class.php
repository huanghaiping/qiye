<?php
use Org\Pay\ThinkPay;
class AlipaySDK extends ThinkPay {
	
	var $alipay_config;
	/**
	 *支付宝网关地址（新）
	 */
	var $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';
	/**
	 * HTTPS形式消息验证地址
	 */
	var $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
	/**
	 * HTTP形式消息验证地址
	 */
	var $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';
	
	protected function config() {
		$alipay_config ['partner'] = $this->APPID; //合作身份者id，以2088开头的16位纯数字
		$alipay_config ['seller_email'] = $this->MCHID; //收款支付宝账号
		$alipay_config ['key'] = $this->APPSECRET; //安全检验码，以数字和字母组成的32位字符
		$alipay_config ['sign_type'] = strtoupper ( 'MD5' ); //签名方式 不需修改
		$alipay_config ['input_charset'] = strtolower ( 'utf-8' ); //字符编码格式 目前支持 gbk 或 utf-8
		$alipay_config ['cacert'] = LIB_PATH . 'Org/Pay/'.__CLASS__ .'/Cert/cacert.pem'; //请保证cacert.pem文件在当前文件夹目录中,ca证书路径地址，用于curl中ssl校验
		$alipay_config ['transport'] = 'http'; //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		 
		return $alipay_config;
	}
	/**
	 * 组装接口调用参数 并调用接口
	 * @param  string $api    微博API
	 * @param  string $param  调用API的额外参数
	 * @param  string $method HTTP请求方法 默认为GET
	 * @return json
	 */
	public function call($shop_info = array()) {
		$this->alipay_config = $this->config (); 
		//支付类型
		$body = "";
		$anti_phishing_key = ""; //防钓鱼时间戳,若要使用请调用类文件submit中的query_timestamp函数
		$exter_invoke_ip = get_client_ip ();//客户端的IP地址
		//非局域网的外网IP地址，如：221.0.0.1
		//构造要请求的参数数组，无需改动
		$parameter=array();
		if (is_mobile_request()){
			$parameter['service']="alipay.wap.create.direct.pay.by.user"; //wap端：alipay.wap.create.direct.pay.by.user
		}else{
			$parameter['service']="create_direct_pay_by_user";
		}
		$parameter['partner']= trim ( $this->alipay_config ['partner'] );
		$parameter['seller_email']=trim ( $this->alipay_config ['seller_email'] );
		$parameter['payment_type']="1";
		$parameter['notify_url']=$shop_info['notify_url'];//需http://格式的完整路径，不能加?id=123这类自定义参数
		$parameter['return_url']=$shop_info['return_url'];//需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/
		$parameter['out_trade_no']=$shop_info ['order_id'];//商户网站订单系统中唯一订单号，必填
		$parameter['subject']=$shop_info ['title'];//订单名称
		$parameter['total_fee']=$shop_info ['total_price'];//付款金额
		$parameter['body']=$body; //商品描述
		$parameter['show_url']=$shop_info ['url'];//商品展示地址,需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html
		$parameter['anti_phishing_key']=$anti_phishing_key;
		$parameter['exter_invoke_ip']=$exter_invoke_ip;
		$parameter['_input_charset']=trim ( strtolower ( $this->alipay_config ['input_charset'] ) ) ;
		$html_text = $this->buildRequestParaToString ( $parameter );
		$html_text = $this->alipay_gateway_new . $html_text;
		return  array('status'=>1,"msg"=>L("ORDER_HAS_BEEN_PAID"),'url'=>$html_text,'order_sn'=>$shop_info['order_id']);
	}
	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	protected function buildRequestMysign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring ( $para_sort );
		
		$mysign = "";
		switch (strtoupper ( trim ( $this->alipay_config ['sign_type'] ) )) {
			case "MD5" :
				$mysign = $this->md5Sign ( $prestr, $this->alipay_config ['key'] );
				break;
			default :
				$mysign = "";
		}
		
		return $mysign;
	}
	
	/**
	 * 生成要请求给支付宝的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组
	 */
	protected function buildRequestPara($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter ( $para_temp );
		
		//对待签名参数数组排序
		$para_sort = $this->argSort ( $para_filter );
		
		//生成签名结果
		$mysign = $this->buildRequestMysign ( $para_sort );
		
		//签名结果与签名方式加入请求提交参数组中
		$para_sort ['sign'] = $mysign;
		$para_sort ['sign_type'] = strtoupper ( trim ( $this->alipay_config ['sign_type'] ) );
		return $para_sort;
	}
	/**
	 * 生成要请求给支付宝的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组字符串
	 */
	protected function buildRequestParaToString($para_temp) {
		//待请求参数数组
		$para = $this->buildRequestPara ( $para_temp );
		//把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
		$request_data = $this->createLinkstringUrlencode ( $para );
		
		return $request_data;
	}
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	protected function createLinkstringUrlencode($para) {
		$arg = "";
		while ( list ( $key, $val ) = each ( $para ) ) {
			$arg .= $key . "=" . urlencode ( $val ) . "&";
		}
		//去掉最后一个&字符
		$arg = substr ( $arg, 0, count ( $arg ) - 2 );
		
		//如果存在转义字符，那么去掉转义
		if (get_magic_quotes_gpc ()) {
			$arg = stripslashes ( $arg );
		}
		
		return $arg;
	}
	
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	protected function createLinkstring($para) {
		$arg = "";
		while ( list ( $key, $val ) = each ( $para ) ) {
			$arg .= $key . "=" . $val . "&";
		}
		//去掉最后一个&字符
		$arg = substr ( $arg, 0, count ( $arg ) - 2 );
		
		//如果存在转义字符，那么去掉转义
		if (get_magic_quotes_gpc ()) {
			$arg = stripslashes ( $arg );
		}
		
		return $arg;
	}
	
	/**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	protected function paraFilter($para) {
		$para_filter = array ();
		while ( list ( $key, $val ) = each ( $para ) ) {
			if ($key == "sign" || $key == "sign_type" || $val == "" || $key == "_URL_")
				continue;
			else
				$para_filter [$key] = $para [$key];
		}
		return $para_filter;
	}
	
	/**
	 * 对数组排序
	 * @param $para 排序前的数组
	 * return 排序后的数组
	 */
	protected function argSort($para) {
		ksort ( $para );
		reset ( $para );
		return $para;
	}
	
	/**
	 * 签名字符串
	 * @param $prestr 需要签名的字符串
	 * @param $key 私钥
	 * return 签名结果
	 */
	protected function md5Sign($prestr, $key) {
		$prestr = $prestr . $key;
		return md5 ( $prestr );
	}
	
	/**
	 * 验证签名
	 * @param $prestr 需要签名的字符串
	 * @param $sign 签名结果
	 * @param $key 私钥
	 * return 签名结果
	 */
	protected function md5Verify($prestr, $sign, $key) {
		$prestr = $prestr . $key;
		$mysgin = md5 ( $prestr );
		if ($mysgin == $sign) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 支付宝页面跳转同步通知页面
	 * Enter description here ...
	 */
	public function alipay_notify($data = array()) {
		if (empty ( $_GET )) { //判断POST来的数组是否为空
			return false;
		} else {
			$this->alipay_config = $this->config ();
			//生成签名结果
			

			$isSign = $this->getSignVeryfy ( $_GET, $_GET ["sign"] );
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'true';
			if (! empty ( $_GET ["notify_id"] )) {
				$responseTxt = $this->getResponse ( $_GET ["notify_id"] );
			}
			
			//写日志记录
			$isSignStr = $isSign ? true : false;
			$log_text = "responseTxt=" . $responseTxt . "\n return_url_log:isSign=" . $isSignStr . ",";
			$log_text = $log_text . $this->createLinkString ( $_GET );
			$this->logResult ( $log_text );
			//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match ( "/true$/i", $responseTxt ) && $isSign) {
				$out_trade_no = $_GET ['out_trade_no']; //商户订单号
				$trade_no = $_GET ['trade_no']; //支付宝交易号
				$trade_status = $_GET ['trade_status']; //交易状态
				if ($_GET ['trade_status'] == 'TRADE_FINISHED' || $_GET ['trade_status'] == 'TRADE_SUCCESS' || $_GET ['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
					//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//如果有做过处理，不执行商户的业务程序
					return array ("status" => 1, "trade_status" => $trade_status, "order_id" => $out_trade_no );
				} else {
					return array ("status" => 0, "trade_status" => $trade_status, "order_id" => $out_trade_no );
				}
			} else {
				return false;
			}
		}
	}
	
	/**
	 * 针对notify_url验证消息是否是支付宝发出的合法消息
	 * @return 验证结果
	 */
	public function verifyNotify() {
		if (empty ( $_POST )) { //判断POST来的数组是否为空
			return false;
		} else {
			$this->alipay_config = $this->config ();
			//生成签名结果
			$isSign = $this->getSignVeryfy ( $_POST, $_POST ["sign"] );
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'true';
			if (! empty ( $_POST ["notify_id"] )) {
				$responseTxt = $this->getResponse ( $_POST ["notify_id"] );
			}
			
			//写日志记录
			$isSignStr = $isSign ? true : false;
			$log_text = "responseTxt=" . $responseTxt . "\n return_url_log:isSign=" . $isSignStr . ",";
			$log_text = $log_text . $this->createLinkString ( $_POST );
			$this->logResult ( $log_text );
			
			//验证
			//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match ( "/true$/i", $responseTxt ) && $isSign) {
				$out_trade_no = $_POST ['out_trade_no']; //商户订单号
				$trade_no = $_POST ['trade_no']; //支付宝交易号
				$trade_status = $_POST ['trade_status']; //交易状态
				if ($_POST ['trade_status'] == 'TRADE_FINISHED' || $_POST ['trade_status'] == 'TRADE_SUCCESS' || $_POST ['trade_status'] == 'WAIT_SELLER_SEND_GOODS') {
					//判断该笔订单是否在商户网站中已经做过处理
					//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
					//如果有做过处理，不执行商户的业务程序
					return array ("status" => 1, "trade_status" => $trade_status, "order_id" => $out_trade_no );
				} else {
					return array ("status" => 0, "trade_status" => $trade_status, "order_id" => $out_trade_no );
				}
			} else {
				return false;
			}
		}
	}
	
	/**
	 * 获取返回时的签名验证结果
	 * @param $para_temp 通知返回来的参数数组
	 * @param $sign 返回的签名结果
	 * @return 签名验证结果
	 */
	protected function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter ( $para_temp );
		
		//对待签名参数数组排序
		$para_sort = $this->argSort ( $para_filter );
		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring ( $para_sort );
		
		$isSgin = false;
		switch (strtoupper ( trim ( $this->alipay_config ['sign_type'] ) )) {
			case "MD5" :
				$isSgin = $this->md5Verify ( $prestr, $sign, $this->alipay_config ['key'] );
				break;
			default :
				$isSgin = false;
		}
		
		return $isSgin;
	}
	
	/**
	 * 获取远程服务器ATN结果,验证返回URL
	 * @param $notify_id 通知校验ID
	 * @return 服务器ATN结果
	 * 验证结果集：
	 * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
	 * true 返回正确信息
	 * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
	 */
	protected function getResponse($notify_id) {
		$transport = strtolower ( trim ( $this->alipay_config ['transport'] ) );
		$partner = trim ( $this->alipay_config ['partner'] );
		$veryfy_url = '';
		if ($transport == 'https') {
			$veryfy_url = $this->https_verify_url;
		} else {
			$veryfy_url = $this->http_verify_url;
		}
		$veryfy_url = $veryfy_url . "partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = $this->getHttpResponseGET ( $veryfy_url, $this->alipay_config ['cacert'] );
		
		return $responseTxt;
	}
	
	/**
	 * 远程获取数据，GET模式
	 * 注意：
	 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
	 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
	 * @param $url 指定URL完整路径地址
	 * @param $cacert_url 指定当前工作目录绝对路径
	 * return 远程输出的数据
	 */
	protected function getHttpResponseGET($url, $cacert_url) {
		$curl = curl_init ( $url );
		curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 显示输出结果
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, true ); //SSL证书认证
		curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); //严格认证
		curl_setopt ( $curl, CURLOPT_CAINFO, $cacert_url ); //证书地址
		$responseText = curl_exec ( $curl );
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close ( $curl );
		
		return $responseText;
	}
	
	/**
	 * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
	 * 注意：服务器需要开通fopen配置
	 * @param $word 要写入日志里的文本内容 默认值：空值
	 */
	protected function logResult($word = '') {
		$fp = fopen ( UPLOAD_PATH_URL . "/paylog/log_" . date ( "Ymd" ) . ".txt", "a+" );
		flock ( $fp, LOCK_EX );
		fwrite ( $fp, "执行日期：" . strftime ( "%Y%m%d%H%M%S", time () ) . "\n" . $word . "\n" );
		flock ( $fp, LOCK_UN );
		fclose ( $fp );
	}
	
	/**
	 * 获取转账收取的手续费
	 * 支付宝转帐规则：
		1、按照用户转帐金额计算手续费
		2、直接扣除转帐金额的手续费，比如：转100元，则实际转入用户为99.9元
		3、按照支付宝规则扣除转帐手续费（每笔0.1%的手续费，最高10元，最低0.5元）
	 * @param float $total_price	提现的金额
	 */
	public function service_charge($total_price) {
		$service_charge = floatval ( $total_price * 0.001 );
		if ($service_charge < 0.5)
			$service_charge = 0.5;
		if ($service_charge > 10)
			$service_charge = 10;
		return $service_charge;
	}

}

?>