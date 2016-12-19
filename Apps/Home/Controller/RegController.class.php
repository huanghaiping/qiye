<?php
namespace Home\Controller;
class RegController extends CommonController {
	
	protected $user_model = "";
	
	/**
	 * 初始化赋值给USER对象
	 * @see CommonController::_initialize()
	 */
	public function _initialize() {
		parent::_initialize ();
		$this->user_model = D ( "User" );
	}
	
	/**
	 * 邮箱注册页面
	 * Enter description here ...
	 */
	public function index() {
		if (! empty ( $this->userInfo )) {
			redirect ( U ( 'User/index' ) ); //跳到自己的个人中心
		}
		if (IS_POST) {
			$data = array ();
			import ( "Org.Util.Input" );
			$nickname = isset ( $_POST ['username'] ) ? \Input::getVar ( I ( 'post.username' ) ) : "";
			$data ['password'] = isset ( $_POST ['password'] ) ? \Input::getVar ( I ( 'post.password' ) ) : "";
			$data ['confirm_password'] = isset ( $_POST ['confirm_password'] ) ? \Input::getVar ( I ( 'post.confirm_password' ) ) : "";
			$data ['verify'] = isset ( $_POST ['verify'] ) ? \Input::getVar ( I ( 'post.verify' ) ) : "";
			if (empty($nickname)){
				$this->error(L('ENTER_YOUR_ACCOUNT_NUMBER'));
			}
			$field="nickname";
			if(checkEmailFormat($nickname)){
				$field="email";
			}elseif (valdeTel($nickname)){
				$field="mobile";
			}else{
				$field="nickname";
				//默认情况下用正则进行验证
				if(mb_strlen($nickname,"utf-8")>20){
					$this->error(L('USER_NAME_LENGTH_20'));
				}
			}
			$data[$field]=$nickname;
			$is_exists_user=$this->user_model->checkUidByField($field,$nickname);
			if ($is_exists_user){
				$this->error(L('ACCOUNT_ALREADY_EXISTS'));
			}
			$Verify = new \Think\Verify ();
			if (! $Verify->check ( $data ['verify'] )) {
				$this->error ( L('VERIFICATION_CODE_ERROR') );
			}
			$rules = array (
				array ('password', 'require', L('PLEASE_INPUT_A_PASSWORD') ), //默认情况下判断密码是否为空
				array ('confirm_password', 'password', L('CONFIRM_PASSWORD_ERROR'), 0, 'confirm' ), // 验证确认密码是否和密码一致
				array ('verify', 'require', L('VERIFICATION_CODE_IS_EMPTY') ) 
			);
			if ($this->user_model->validate ( $rules )->create ( $data )) {
				$data ["password"] = md5 ( md5 ( $data ["password"] ) );
				$data ['mtype'] == $data ['usertype'] = 0;
				unset ( $data ['confirm_password'] );
				unset ( $data ['verify'] );
				$user_id = $this->user_model->reg_data ( $data );
				if ($user_id) {
					//写入session
					$sessionInfo = array ("uid" => $user_id, "name" => $data ['nickname'], "faceurl" => "" );
					session ( "USER_INFO", $sessionInfo ); //保存登录的信息
					//发送验证邮箱的邮件
					redirect(U ( "User/index" ) );
				} else {
					$this->error ( L('LOGIN_HAS_FAILED') );
				}
			} else {
				$this->error ( $this->user_model->getError () );
			}
		} else {
			$this->get_seo_info (array('site_title'=>L('REGISTER')));
			$this->display ();
		}
	}
	
	/**
	 * 检查邮箱是否注册
	 * Enter description here ...
	 */
	public function checkEmail() {
		$email = isset ( $_POST ['email'] ) ? $_POST ['email'] : "";
		if (empty ( $email )) {
			$this->error ( L('MAILBOX_IS_EMPTY') );
		}
		if (! checkEmailFormat ( $email )) {
			$this->error ( L('MAILBOX_FORMAT_ERROR') );
		}
		$result = $this->user_model->checkUidByField ( "email", $email );
		if ($result) {
			$this->error ( L('MAILBOX_ALREADY_EXISTS') );
		} else {
			$this->success ( L('MAILBOX_CAN_BE_USED') );
		}
	}
	
	/**
	 * 检查手机号码是否注册。
	 * Enter description here ...
	 */
	public function checkMobile() {
		$mobile = isset ( $_POST ['mobile'] ) ? $_POST ['mobile'] : "";
		if (empty ( $mobile )) {
			$this->error (L('CELL_PHONE_NUMBER_IS_EMPTY') );
		}
		$result = $this->user_model->checkUidByField ( "mobile", $mobile );
		if ($result) {
			$this->error ( L('CELL_PHONE_NUMBER_ALREADY_EXISTS') );
		} else {
			$this->success ( L('CELL_PHONE_NUMBER_CAN_BE_USED') );
		}
	}
	
	/**
	 * 手机号码注册
	 * Enter description here ...
	 */
	public function mobile() {
		if (! empty ( $this->userInfo )) {
			redirect ( U ( 'User/index' ) ); //跳到自己的个人中心
		}
		$data = array ();
		import ( "Org.Util.Input" );
		$data ['mobile'] = isset ( $_POST ['mobile'] ) ? \Input::getVar ( I ( 'post.mobile' ) ) : "";
		$data ['nickname'] = isset ( $_POST ['nickname'] ) ? \Input::getVar ( I ( 'post.nickname' ) ) : "";
		$data ['password'] = isset ( $_POST ['password'] ) ? \Input::getVar ( I ( 'post.password' ) ) : "";
		$data ['confirm_password'] = isset ( $_POST ['confirm_password'] ) ? \Input::getVar ( I ( 'post.confirm_password' ) ) : "";
		$data ['verify'] = isset ( $_POST ['verify'] ) ? \Input::getVar ( I ( 'post.verify' ) ) : "";
		$rules = array (
		array ('mobile', 'require', L('CELL_PHONE_NUMBER_IS_EMPTY')  ), //验证邮箱
		array ('mobile', '', L('CELL_PHONE_NUMBER_ALREADY_EXISTS'), 1, 'unique', 1 ), // 当值不为空的时候判断邮箱是否存在
		array ('nickname', 'require', L('ENTER_YOUR_ACCOUNT_NUMBER') ), //默认情况下判断用户名是否为空
		array ('password', 'require', L('PLEASE_INPUT_A_PASSWORD') ), //默认情况下判断密码是否为空
		array ('confirm_password', 'password', L('CONFIRM_PASSWORD_ERROR'), 0, 'confirm' ), // 验证确认密码是否和密码一致
		array ('verify', 'require', L('VERIFICATION_CODE_IS_EMPTY') ) );//默认情况下用正则进行验证

		if ($this->user_model->validate ( $rules )->create ( $data )) {
			//检查手机验证码是否正确
			$verify_model = D ( "Verify" );
			$is_model = $verify_model->updateVerify ( $data ['mobile'], $data ['verify'], 1 );
			if (! $is_model) {
				$this->error ( L('VERIFICATION_CODE_ERROR') );
			}
			$data ["password"] = md5 ( md5 ( $data ["password"] ) );
			$data ['mtype'] == $data ['usertype'] = 0;
			unset ( $data ['confirm_password'] );
			unset ( $data ['verify'] );
			$user_id = $this->user_model->reg_data ( $data );
			if ($user_id) {
				//写入session
				$sessionInfo = array ("uid" => $user_id, "name" => $data ['nickname'], "login_type" => "0" );
				session ( "USER_INFO", $sessionInfo ); //保存登录的信息
				$this->success ( L('LOGIN_WAS_SUCCESSFUL'), U ( "User/index" ) );
			} else {
				$this->error ( L('LOGIN_HAS_FAILED') );
			}
		} else {
			$this->error ( $this->user_model->getError () );
		}
	}
	
	/**
	 * 用户退出机制
	 * Enter description here ...
	 */
	public function logout() {
		session ( "USER_INFO", null );
		session ( NULL );
		cookie ( NULL );
		session_unset (); //清空session变量
		session_destroy (); //销毁session数据
		redirect(U ( 'login/index' ));
	}
	
	/**
	 * 找回密码
	 * Enter description here ...
	 */
	public function sendpwd() {
		if (IS_POST) {
			import ( "Org.Util.Input" );
			$username = isset ( $_POST ['username'] ) ? \Input::getVar ( I ( 'post.username' ) ) : "";
			$verify = isset ( $_POST ['verify'] ) ? \Input::getVar ( I ( 'post.verify' ) ) : "";
			if (empty($username)){
				$this->error(L('MAILBOX_IS_EMPTY'));
			}
			$Verify = new \Think\
			Verify ();
			if (! $Verify->check ( $verify )) {
				$this->error ( L('VERIFICATION_CODE_ERROR'), U ( 'Reg/sendpwd' ) );
			}
			$user_model = D ( "User" );
			$verify_model = D ( "Verify" );
			if (checkEmailFormat ( $username )) { //当是邮箱的时候
				$uid = $user_model->checkUidByField ( "email", $username );
				if (! $uid) {
					$this->error ( L('MAILBOX_NOT_REGISTERED') );
				}
				$user_info = $user_model->where ( "uid='{$uid}'" )->field ( "nickname,password" )->find ();
				$name = $userid = $user_info ['nickname'];
				$pass = $user_info ["password"];
				$x = md5 ( $uid . '+' . $pass );
				$code = base64_encode ( $uid . "." . $x . "." . time () );
				$change_pass_url = "http://" . $_SERVER ['HTTP_HOST'] . U ( 'Reg/resetPwd', array ('p' => $code ) );
				$templet_param = array ('name' => $name, 'url' => $change_pass_url, "email" => $username, "name" => $name );
				$result = $verify_model->sendmail ( "SEND_EMAIL_FIND_PASSWORD", $templet_param ); //发送邮件
				if ($result) {
					redirect ( U ( "Reg/send_email" ) );
				} else {
					$this->error ( L('FAIL_IN_SEND'), U ( 'sendpwd' ) );
				}
			} elseif (valdeTel ( $username )) { //当是手机找回密码的时候
				$uid = $user_model->checkUidByField ( "mobile", $username );
				if (! $uid) {
					$this->error ( L('PHONE_NUMBER_NOT_REGISTERED') );
				}
				$send_type = 2;
				$result = $verify_model->sendDx ( $username, $send_type );
				if ($result) {
					redirect ( U ( 'check', array ('mobile' => $username, "type" => $send_type ) ) );
				} else {
					$this->error ( $verify_model->getError () );
				}
			
			} else {
				$this->error ( L('ACCOUNT_ERROR'), U ( 'Reg/sendpwd' ) );
			}
		
		} else {
				$this->get_seo_info (array('site_title'=>L('RETRIEVE_PASSWORD')));
			$this->display ();
		}
	}
	
	/**
	 * +----------------------------------------
	 * 重设密码
	 * +----------------------------------------
	 */
	public function resetpwd() {
		$p = $_GET ['p'];
		$p = base64_decode ( $p );
		if ($p) {
			$info = explode ( '.', $p );
			$userid = $info [0];
			$times = $info [2];
			if (time () - $times > 3600) {
				$this->error ( L('URL_LINK_HAS_EXPIRED'), U ( 'sendpwd' ) );
			}
			$Mem = D ( "User" );
			$user = $Mem->where ( "uid='" . $userid . "'" )->getField ( "password" );
			if ($user) {
				$x = md5 ( $userid . '+' . $user );
				if ($x != $info [1]) {
					$this->error ( L('PARAMETER_ERROR'), U ( 'sendpwd' ) );
				}
				$userid = base64_encode ( $userid . "#" . $user );
				$this->assign ( "userid", $userid );
				//$this->get_seo_info ();
				$this->display ();
			
			} else {
				$this->error ( L('PARAMETER_ERROR'), U ( 'sendpwd' ) );
			}
		
		} else {
			$this->error ( L('PARAMETER_ERROR'), U ( 'sendpwd' ) );
		}
	}
	
	/**
	 * 重置密码
	 * Enter description here ...
	 */
	public function saveRestPwd() {
		
		$userid = isset ( $_POST ["userid"] ) ? $_POST ["userid"] : "";
		$newpwd = isset ( $_POST ['password'] ) ? $_POST ['password'] : "";
		$newpwd2 = isset ( $_POST ['re_password'] ) ? $_POST ['re_password'] : "";
		if ($newpwd != $newpwd2 || empty ( $newpwd )) {
			$this->error ( L('CONFIRM_PASSWORD_ERROR') );
		}
		$userid = base64_decode ( $userid );
		if ($userid) {
			$user_arr = explode ( "#", $userid );
			$userid = $user_arr [0];
			$oldpwd = $user_arr [1];
			$Mem = D ( "User" );
			$user = $Mem->where ( "uid='" . $userid . "'" )->getField ( "password" ); //验证是否是重置的用户
			if ($oldpwd != $user) {
				$this->error ( L('PARAMETER_ERROR'), U ( 'sendpwd' ) );
			}
			$new_pwd = md5 ( md5 ( $newpwd ) );
			$result = $Mem->where ( "uid='" . $userid . "'" )->setField ( "password", $new_pwd );
			if ($result) {
				redirect ( U ( "finish" ) );
			} else {
				$this->error ( L('RESET_FAILED'), U ( 'sendpwd' ) );
			}
		
		} else {
			$this->error ( L('PARAMETER_ERROR'), U ( 'sendpwd' ) );
		}
	}
	
	/**
	 * 手机号码重置密码
	 * Enter description here ...
	 */
	public function check() {
		$user_model = D ( "User" );
		if (IS_POST) {
			$mobile = isset ( $_POST ['mobile'] ) ? addslashes ( $_POST ['mobile'] ) : "";
			if (! valdeTel ( $mobile )) {
				$this->error ( L('CELL_PHONE_NUMBER_FORMAT_ERROR'));
			}
			$type = isset ( $_POST ['type'] ) ? intval ( $_POST ['type'] ) : 2;
			$verify = isset ( $_POST ['verify'] ) ? intval ( $_POST ['verify'] ) : "";
			if (empty ( $verify )) {
				$this->error ( L('VERIFICATION_CODE_IS_EMPTY'));
			}
			$uid = $user_model->checkUidByField ( "mobile", $mobile );
			if (! $uid) {
				$this->error (L('PHONE_NUMBER_NOT_REGISTERED'), U ( 'Reg/index' ) );
			}
			$verify_model = D ( "Verify" );
			$result = $verify_model->updateVerify ( $mobile, $verify, $type );
			if ($result) {
				$user_info = $user_model->where ( "uid='{$uid}'" )->field ( "nickname,password" )->find ();
				$name = $userid = $user_info ['nickname'];
				$pass = $user_info ["password"];
				$x = md5 ( $uid . '+' . $pass );
				$code = base64_encode ( $uid . "." . $x . "." . time () );
				$change_pass_url = U ( 'Reg/resetPwd', array ('p' => $code ) );
				redirect ( $change_pass_url );
			} else {
				$this->error ( L('VERIFICATION_CODE_ERROR') );
			}
		
		} else {
			$mobile = isset ( $_GET ['mobile'] ) ? addslashes ( $_GET ['mobile'] ) : "";
			if (! valdeTel ( $mobile )) {
				$this->error (  L('CELL_PHONE_NUMBER_FORMAT_ERROR') );
			}
			$uid = $user_model->checkUidByField ( "mobile", $mobile );
			if (! $uid) {
				$this->error ( L('PHONE_NUMBER_NOT_REGISTERED'), U ( 'Reg/index' ) );
			}
			$type = isset ( $_GET ['type'] ) ? intval ( $_GET ['type'] ) : 2;
			$this->assign ( "mobile", $mobile );
			$this->assign ( "type", $type );
			$this->display ();
		}
	}
	
/**
	 * 验证邮箱的可用性
	 * Enter description here ...
	 */
	public function checkmail() {
		$p = $_GET ['p'];
		$p = base64_decode ( $p );
		if ($p) {
			$info = explode ( '.', $p );
			$userid = $info [0];
			$times = $info [2];
			if (time () - $times > 3600) {
				$this->error ( L('URL_LINK_HAS_EXPIRED'), U ( 'Index/index' ) );
			}
			$Mem = D ( "User" );
			$user = $Mem->where ( "uid='" . $userid . "'" )->getField ( "password" );
			if ($user) {
				$x = md5 ( $userid . '+' . $user );
				if ($x != $info [1]) {
					$this->error ( L('PARAMETER_ERROR'), U ( 'Index/index' )  );
				}
				D ( 'UserInfo' )->where ( "mid='" . $userid . "'" )->setField ( "is_email", 1 );
				$this->display ();
			
			} else {
				$this->error ( L('PARAMETER_ERROR'), U ( 'Index/index' )  );
			}
		
		} else {
			$this->error ( L('PARAMETER_ERROR'), U ( 'Index/index' )  );
		}
	}
}