<?php
namespace Home\Model;
class VerifyModel extends CommonModel {
	
	protected $username = "zhumingchao";
	protected $password = "zhu6213592";
	
	/**
	 * +---------------------------------------------------
	 * 发送手机短信
	 * +---------------------------------------------------
	 * 
	 * @param 	string 	$mobile		手机号码
	 * @param 	int 	$type		短信类型 1 注册的时候验证码，2找回密码
	 */
	public function sendDx($mobile, $type = 1) {
		if (empty ( $mobile ))
			return false;
		$map ['mobile'] = $mobile;
		$map ['type'] = $type;
		$result = $this->where ( $map )->field ( "ctime,verify,status" )->limit ( 1 )->find ();
		$verify = rand ( 0, 9 ) . rand ( 0, 9 ) . rand ( 0, 9 ) . rand ( 0, 9 ) . rand ( 0, 9 ) . rand ( 0, 9 );
		$dx_txt = '[妮宝]您正在进行手机号码验证，效验码:' . $verify . " 打死都不能告诉别人哦";
		$url = "http://api.smsbao.com/sms?u=" . $this->username . "&p=" . md5 ( $this->password ) . "&m=" . $mobile . "&c=" . urlencode ( $dx_txt );
		if ($result) {
			if ($result ['status'] && $type == 1) {
				$this->error = "手机号码已经注册了";
				return false;
			} else {
				if (time () - $result ['ctime'] < 60) {
					$this->error = "请一分钟后再重发";
					return false;
				} else {
					$_result = get_url_content ( $url );
					if ($_result == "0") {
						$this->where ( $map )->save ( array ("verify" => $verify, "ctime" => time (), "return_status" => $_result, "status" => 0 ) );
						return true;
					} else {
						$this->error = "短信发送失败";
						return false;
					}
				}
			}
		} else {
			$_result = get_url_content ( $url );
			if ($_result == "0") {
				$data = array ("mobile" => $mobile, "verify" => $verify, "ctime" => time (), "return_status" => $_result, "userip" => get_client_ip (), "type" => $type );
				$last_id = $this->add ( $data );
				return true;
			} else {
				$this->error = "短信发送失败";
				return false;
			}
		}
	}
	
	/**
	 * +---------------------------------------------------
	 * 检查验证码是否正确不更改状态
	 * +---------------------------------------------------
	 * 
	 * @param int 	$mobile		手机号码
	 * @param int 	$verify		短信验证码
	 */
	public function checkVerify($mobile, $verify, $type = 1) {
		if (empty ( $mobile ) || empty ( $verify ))
			return false;
		$map ['mobile'] = $mobile;
		$map ['type'] = $type;
		$map ['status'] = 0;
		$result = $this->where ( $map )->field ( "id,ctime,verify,status" )->limit ( 1 )->find ();
		if (! $result)
			return false;
		if ($result ['verify'] == $verify)
			return true;
		else
			return false;
	}
	
	/**
	 * +---------------------------------------------------
	 * 检查验证码是否正确同时更改状态
	 * +---------------------------------------------------
	 * 
	 * @param 	int 	$mobile		手机号码
	 * @param 	int 	$verify		短信验证码
	 * @param	int		$type		短信类型
	 */
	public function updateVerify($mobile, $verify, $type = 1) {
		if (empty ( $mobile ) || empty ( $verify ))
			return false;
		$map ['mobile'] = $mobile;
		$map ['type'] = $type;
		$map ['status'] = 0;
		$result = $this->where ( $map )->field ( "id,ctime,verify,status" )->limit ( 1 )->find ();
		if (! $result)
			return false;
		if ($result ['verify'] != $verify)
			return false;
		$result = $this->where ( "id='" . $result ['id'] . "'" )->save ( array ("status" => 1, "check_time" => time () ) );
		if ($result) { //验证成功，就更改手机号码已验证状态
			return true;
		}
		return false;
	}
	
	/**
	 * +---------------------------------------------------
	 * 发送邮件
	 * +---------------------------------------------------
	 * 
	 * @param	string		$key		邮件的key
	 * @param	string		$info		发送的内容
	 */
	public function sendmail($key, $info = array()) {
		if (empty ( $key ) || ! isset ( $info ['email'] ))
			return false;
		$Template_model = D ( "Template" );
		$templet = $Template_model->getSystemTemp ( $key, $info );
		if ($templet){
			$reuslt = $this->sendemail ( $info ['email'], $templet ['title'], $templet ['content'], $info ['name'] ); //发送邮件
			return $reuslt;
		}else{
			return false;
		}
	}
	
	/**
	 * +---------------------------------------------------
	 * 生成邮件配置信息
	 * +---------------------------------------------------
	 */
	protected function createEmailConfig(){
		$email_config=F("EmailConfig",'',INCLUDE_PATH);
		return $email_config;
	}
	
	/**
	 * +---------------------------------------------------
	 * 生成邮件对象
	 * +---------------------------------------------------
	 * 
	 * @param string 	$to			接收者邮件
	 * @param string 	$subject	邮件的主题
	 * @param string 	$body		邮件主题内容
	 * @param string 	$username	接收者姓名
	 */
	public  function sendemail($to,$subject,$body,$username){
		$config=$this->createEmailConfig();
		if (!$config||($config&&$config['status']==0)){
			return false;
		}
		require_once './Public/sendmail/class.phpmailer.php';
		$mail  = new \PHPMailer();
		$mail->IsSMTP();	            				// 启用SMTP
		$mail->Host = $config['smtp'];        			//SMTP服务器smtp.qq.com
		$mail->SMTPAuth = true;               			//开启SMTP认证
		$mail->Username = $config['accout'];     		// SMTP用户名 451648237@qq.com
		$mail->Password = $config['password'];         	// SMTP密码 ugee87537115
		$mail->SMTPDebug=false;
		$mail->From = $config['from_name'];         	//发件人地址 mailservice@linghit.com
		$mail->FromName = $config['fromusername'];                //发件人
		$mail->AddAddress($to, $username);        	 	//添加收件人
		$mail->AddReplyTo($to, $username);
		$mail->CharSet = "UTF-8"; 						// 这里指定字符集！
		$mail->Encoding = "base64"; 
		$mail->WordWrap = 50;                    		//设置每行字符长度
		$mail->IsHTML(true);                			// 是否HTML格式邮件
		$mail->Subject = $subject;      				//邮件主题
		$mail->Body    = $body;        					//邮件内容
		$mail->AltBody ="text/html"; 
		$result=$mail->Send();
		if ($result){
			return array("status"=>1,"msg"=>"");
		}else{
			$error_info=$mail->ErrorInfo;
			return array("status"=>0,"msg"=>$error_info);
		}
	}
}