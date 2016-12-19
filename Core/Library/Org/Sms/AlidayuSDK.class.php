<?php
use Org\Sms\ThinkSms;
class AlidayuSDK extends ThinkSms {
	
	/**
	 * 发送短信验证码
	 * Enter description here ...
	 */
	public function send($mobile,$verify_id='') {
		if (! $mobile) {
			return false;
		}
		require_once "Alidayu/TopSdk.php";
		date_default_timezone_set ( 'Asia/Shanghai' );
		$c = new TopClient ();
		$c->format = "json";
		$c->appkey = $this->APPKEY;
		$c->secretKey = $this->APPSECRET;
		$req = new AlibabaAliqinFcSmsNumSendRequest ();
		$req->setExtend ( $verify_id );
		$req->setSmsType ( "normal" );
		$req->setSmsFreeSignName ( $this->getSign () );
		$smsParam=$this->getSmsParam();
		$req->setSmsParam ( json_encode ( $smsParam ) );
		$req->setRecNum ( $mobile);
		$req->setSmsTemplateCode ( $this->getTemplate ( $smsParam ) );
		$resp = $c->execute ( $req );
		if ($resp) {
			$result = $this->json_to_array ( $resp );
			if (isset($result['result'])){
				$result=$result['result'];
				if (isset ( $result ['err_code'] ) && isset ( $result ['success'] ) && $result ['err_code'] == 0 && $result ['success']) {
					return array ("status" => 1, "msg" => isset($result ['sub_msg']) ? $result ['sub_msg'] : $result['msg'] ,"verify"=>$smsParam['number']);
				}
			}
			if ($result ['code']==15){
			 	$result ['sub_msg']="短信发送太频繁";
			}
			return array ("status" => 0, "msg" => isset($result ['sub_msg']) ? $result ['sub_msg'] : $result['msg'], 'code' => $result ['code'], "sub_code"=>$result['sub_code']);
		} else {
			return array ("status" => 0, "msg" => "短信发送失败" );
		}
	}
	
	/**
	 * 将json转换为数组
	 * Enter description here ...
	 * @param unknown_type $web json字符串
	 */
	protected function json_to_array($web) {
		$arr = array ();
		foreach ( $web as $k => $w ) {
			if (is_object ( $w ))
				$arr [$k] = $this->json_to_array ( $w ); //判断类型是不是object
			else
				$arr [$k] = $w;
		}
		return $arr;
	}

}