<?php
use Org\Pay\ThinkPay;
require_once 'Wx/lib/WxPay.Api.php';
class WxSDK extends ThinkPay {
	
	var $wx_config;
	
	/**
	 * 查看wx支付的配置
	 * Enter description here ...
	 */
	protected function config() {
		$wx_config ['APPID'] = $this->APPID;
		$wx_config ['MCHID'] = $this->MCHID;
		$wx_config ['KEY'] = $this->KEY;
		$wx_config ['APPSECRET'] = $this->APPSECRET;
		$wx_config ['SSLCERT_PATH'] = $this->SSLCERT_PATH;
		$wx_config ['SSLKEY_PATH'] = $this->SSLKEY_PATH;
		$wx_config ['CURL_PROXY_HOST'] = "0.0.0.0";
		$wx_config ['CURL_PROXY_PORT'] = 0;
		$wx_config ['REPORT_LEVENL'] = 1;
		return $wx_config;
	}
	

	
	/**
	 * 组装接口调用参数 并调用接口(扫描支付二)
	 * 流程：
	 * 1、调用统一下单，取得code_url，生成二维码
	 * 2、用户扫描二维码，进行支付
	 * 3、支付完成之后，微信服务器会通知支付成功
	 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
		 
	 * @param  string $api    微博API
	 * @param  string $param  调用API的额外参数
	 * @param  string $method HTTP请求方法 默认为GET
	 * @return json
	 */
	public function call($shop_info = array()) {
		ini_set ( 'date.timezone', 'Asia/Shanghai' );
		$this->wx_config = $this->config ();
		if (! empty ( $shop_info ['notify_url'] )) {
			$this->wx_config ['NOTIFY_URL'] = $shop_info ['notify_url'];
		}
		$input = new WxPayUnifiedOrder ( $this->wx_config );
		$input->SetOut_trade_no ( $shop_info ['order_id'] );
		$result = \WxPayApi::orderQuery ( $input );
		 //判断订单是否已付款
		if (array_key_exists ( "return_code", $result ) && array_key_exists ( "result_code", $result ) && $result ["return_code"] == "SUCCESS" && $result ["result_code"] == "SUCCESS") {
			if ($result ['trade_state'] == 'SUCCESS') { //已经下单
				return array ('status' => 2, "msg" => L("ORDER_HAS_BEEN_PAID"), 'url' => U ( 'User/order' ) );
			}
		}
		
		if (is_weixin ()) { //当时在微信浏览器中
			require_once 'Wx/lib/WxPay.JsApi.php'; 
			$tools = new \JsApiPay ( $this->wx_config );
			$baseUrl=C ( "SITE_URL" ) . '/Pay/payment?sn=' . $shop_info ['order_id'] . "&pay_type=" . $shop_info ['pay_type']."&wx=1";
			$shop_info['openId'] = $tools->GetOpenid ( $baseUrl );
			$input->SetOpenid ( $shop_info['openId'] );
			$shop_info ['Trade_type'] = "JSAPI";
			
		} else {
			$shop_info ['Trade_type'] = "NATIVE";
		}
		
		$input = new WxPayUnifiedOrder ( $this->wx_config );
		$result = $this->GetPayUrl ( $input, $shop_info );
		if ($result ['return_code'] == 'FAIL') { // 当通信失败的时候
			return array ('status' => 0, "msg" => $result ['return_msg'], 'url' => '' );
		} else {
			if ($result ['result_code'] == 'FAIL') {
				if ($result ['return_code'] == "SUCCESS" && $result ['err_code'] == 'OUT_TRADE_NO_USED') {
					$input = new WxPayUnifiedOrder ( $this->wx_config );
					$input->SetOut_trade_no ( $shop_info ['order_id'] );
					$result_close = \WxPayApi::closeOrder ( $input ); //关闭原先的订单	
					
					 //重新生成支付订单
					$order_model = D ( "Order" );
					$shop_info ['order_id'] = $order_model->updateOrderSn ( $shop_info ['order_id'] );
					$input = new WxPayUnifiedOrder ( $this->wx_config );
					$input->SetOut_trade_no ( $shop_info ['order_id'] );
					$result = $this->GetPayUrl ( $input, $shop_info );
				
				} else {
					return array ('status' => 0, "msg" => $result ['err_code'] . ":" . $result ['err_code_des'], 'url' => '' );
				}
			}
		}
		if ($shop_info ['Trade_type'] == "JSAPI") {
			$return = $tools->GetJsApiParameters ( $result );
		} else {
			$url2 = $result ["code_url"];
			$return = U ( 'Wxpay/qrcode' ) . "?data=" . urlencode ( $url2 );
		}
		return array ('status' => 1, "msg" => L("IN_THE_FORM_OF_PAYMENT"), 'url' => $return, 'order_sn' => $shop_info ['order_id'] );
	}
	
	/**
	 * 
	 * 生成扫描支付URL,模式一
	 * @param BizPayUrlInput $bizUrlInfo
	 */
	public function GetPrePayUrl($productId) {
		$biz = new WxPayBizPayUrl ();
		$biz->SetProduct_id ( $productId );
		$values = WxpayApi::bizpayurl ( $biz );
		$url = "weixin://wxpay/bizpayurl?" . $this->ToUrlParams ( $values );
		return $url;
	}
	
	/**
	 * 
	 * 参数数组转换为url参数
	 * @param array $urlObj
	 */
	private function ToUrlParams($urlObj) {
		$buff = "";
		foreach ( $urlObj as $k => $v ) {
			$buff .= $k . "=" . $v . "&";
		}
		$buff = trim ( $buff, "&" );
		return $buff;
	}
	
	/**
	 * 
	 * 生成直接支付url，支付url有效期为2小时,模式二
	 * @param UnifiedOrderInput $input
	 */
	public function GetPayUrl($input, $shop_info) {
		if(isset($shop_info['openId'])&&!empty($shop_info['openId'])){
			$input->SetOpenid ( $shop_info['openId'] );
		}
		$input->SetOut_trade_no ( $shop_info ['order_id'] );
		$body = isset ( $shop_info ['body'] ) ? $shop_info ['body'] : $shop_info ['title']; //商品描述
		$input->SetBody ( $body );
		$input->SetGoods_tag ( $body );
		$input->SetAttach ( $shop_info ['title'] );
		$input->SetTrade_type ( $shop_info ['Trade_type'] );
		$input->SetNotify_url ( $shop_info ['notify_url'] );
		$input->SetProduct_id ( $shop_info ['order_id'] );
		$input->SetTime_start ( date ( "YmdHis" ) );
		$input->SetTime_expire ( date ( "YmdHis", time () + 86400 ) );
		$input->SetTotal_fee ( $shop_info ['total_price'] * 100 );
		$result = WxPayApi::unifiedOrder ( $input, 6 );
		return $result;
	}
	
	/**
	 * 
	 *服务器异步通知页面路径
	 *
	 */
	public function verifyNotify() {
		require_once 'Wx/lib/WxPay.Notify.php';
		$this->wx_config = $this->config ();
		$notify = new WxPayNotify ( $this->wx_config );
		$result = $notify->Handle ( true );
		return $result;
	}
}