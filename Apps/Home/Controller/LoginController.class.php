<?php
namespace Home\Controller;
class LoginController extends CommonController {
	
	/**
	 * 用户登录页面
	 * Enter description here ...
	 */
	public function index() {
		if (! empty ( $this->userInfo )) {
			redirect ( U ( 'User/index' ) ); //跳到自己的个人中心
		}
		if (IS_POST) {
			$map = array ();
			import ( "Org.Util.Input" );
			$account_number = isset ( $_POST ['username'] ) ? \Input::getVar ( I ( 'post.username' ) ) : "";
			$paw = isset ( $_POST ['password'] ) ? \Input::getVar ( I ( 'post.password' ) ) : "";
			$jumpUrl= isset ( $_POST ['jumpUrl'] ) ? $_POST ['jumpUrl'] : "";
			if (empty ( $account_number ))
				$this->error ( L('ENTER_YOUR_ACCOUNT_NUMBER'), U ( 'Login/index' ,array('jumpUrl'=>$jumpUrl)) );
			if (empty ( $paw ))
				$this->error ( L('PLEASE_INPUT_A_PASSWORD'), U ( 'Login/index'  ,array('jumpUrl'=>$jumpUrl)) );
			
		//生成认证条件
			if (! checkEmailFormat ( $account_number )) {
				if (! valdeTel ( $account_number )) {
					$map ['nickname'] = $account_number;
				} else {
					$map ['mobile'] = $account_number;
				}
			} else {
				$map ['email'] = $account_number;
			}
			// 支持使用绑定帐号登录
			$Member = D ( 'User' );
			$authInfo = $Member->where ( $map )->field ( "uid,email,nickname,password,status,faceurl" )->limit ( 1 )->find ();
			//使用用户名、密码和状态的方式进行认证
			if (false == $authInfo) {
				$this->error ( L('ACCOUNT_ERROR'), U ( 'Login/index'  ,array('jumpUrl'=>$jumpUrl)) );
			} else {
				if ($authInfo ['password'] != md5 ( md5 ( $paw ) )) {
					$this->error ( L('PASSWORD_ERROR'), U ( 'Login/index' ,array('jumpUrl'=>$jumpUrl) ) );
				}
				if ($authInfo ['status'] == 1) {
					$this->error ( L('SORRY_ACCOUNT _ERROR'));
				}
			}
			$sessionInfo = array ("uid" => $authInfo ['uid'], "name" => $authInfo ['nickname'], "faceurl" => $authInfo ['faceurl'] );
			session ( "USER_INFO", $sessionInfo ); //保存登录的信息
			$Member->where ( "uid='" . $authInfo ['uid'] . "'" )->save ( array ("login_time" => time () ) ); //更改登录的次数和时间
			$jumpUrl = ! empty ($jumpUrl) ? base64_decode ( $_POST ['jumpUrl'] ) : "";
			if (empty ( $jumpUrl )) {
				redirect ( U ( "User/index" ) );
			} else {
				redirect ( $jumpUrl );
			}
		} else {
			$jumpUrl = isset ( $_GET ['jumpUrl'] ) && ! empty ( $_GET ['jumpUrl'] ) ? ($_GET ['jumpUrl']) : "";
			$this->assign ( "jumpUrl", $jumpUrl );
			$this->get_seo_info ( array ('site_title' => L('SIGN_IN') ) );
			$this->display ();
		}
	}
	
	/**
	 * 登陆框
	 * Enter description here ...
	 */
	public function login() {
		if (! empty ( $this->userInfo )) {
			redirect ( U ( "User/index" ) ); //跳到自己的个人中心
		}
		$jump = isset ( $_GET ['jump'] ) ? ($_GET ['jump']) : U ( 'User/index' );
		$this->assign ( "jump", $jump );
		$this->display ();
	}
	
	/**
	 * 第三方平台登录的方法
	 * Enter description here ...
	 * @param string $type 平台标识
	 */
	public function uc($type = null) {
		if (! empty ( $this->userInfo )) {
			redirect ( U ( "User/index" ) );
		}
		empty ( $type ) && $this->error ( L('PARAMETER_ERROR'));
		$login_api_model = D ( ADMIN_NAME . "/LoginApi" );
		$result = $login_api_model->getInfoByTypeName ( $type );
		if (! $result) {
			$this->error ( L('ILLEGAL_REQUEST') );
		}
		//加载ThinkOauth类并实例化一个对象
		$sns = \Org\ThinkSdk\ThinkOauth::getInstance ( $type );
		if (! $sns) {
			$this->error ( L('ILLEGAL_REQUEST') );
		}
		//跳转到授权页面
		redirect ( $sns->getRequestCodeURL () );
	}
	
	/**
	 * 平台授权回调地址
	 * Enter description here ...
	 * @param string $type 平台标识
	 * @param string  $code 平台回调秘钥
	 */
	public function callback($type = null, $code = null) {
		(empty ( $type ) || empty ( $code )) && $this->error (  L('PARAMETER_ERROR'));
		//加载ThinkOauth类并实例化一个对象
		$sns = \Org\ThinkSdk\ThinkOauth::getInstance ( $type );
		if (! $sns) {
			$this->error ( L('ILLEGAL_REQUEST') );
		}
		$extend = null; //腾讯微博需传递的额外参数
		if ($type == 'tencent') {
			$extend = array ('openid' => $this->_get ( 'openid' ), 'openkey' => $this->_get ( 'openkey' ) );
		}
		//请妥善保管这里获取到的Token信息，方便以后API调用
		//调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
		//如： $qq = ThinkOauth::getInstance('qq', $token);
		$token = $sns->getAccessToken ( $code, $extend );
		
		//获取当前登录用户信息
		if (is_array ( $token )) {
			$user_info = $sns->$type ( $token );
			$user_info ['type'] = isset ( $user_info ['type'] ) ? getPlatform ( $user_info ['type'] ) : 0;
			$Members = D ( "User" );
			$UserPlatform = D ( "UserOpen" );
			if ($user_info) {
				$openid = $token ['openid'];
				$info = $info = $UserPlatform->where ( "openid='{$openid}' and type='" . $user_info ['type'] . "'" )->field ( "member_id" )->limit ( 1 )->find ();
				;
				$is_new = false;
				if ($info) { //当是已经注册登录的时候
					$userId = $info ['member_id'];
					$user_rel = $Members->where ( 'uid=' . $userId )->find ();
					if ($user_rel) {
						$nickname = ! empty ( $user_rel ['nickname'] ) ? $user_rel ['nickname'] : $user_info ['nick'];
						$sessionInfo = array ("uid" => $user_rel ['uid'], "name" => $nickname, "login_type" => $user_info ['type'] );
						session ( "USER_INFO", $sessionInfo ); //保存登录的信息
					} else {
						$UserPlatform->where ( "openid='" . $token ['openid'] . "'" )->delete ();
						$is_new = true;
					}
				} else { //当已经是注册过的用户的时候
					$is_new = true;
				}
				if ($is_new) {
					$Members->startTrans (); //启动事务 
					$dataArray = array ("email" => "", "nickname" => $user_info ['nick'], "password" => "", "usertype" => $user_info ['type'] );
					
					$userId = $Members->reg_data ( $dataArray );
					if ($userId) {
						$plat_data = array ("member_id" => $userId, "type" => $user_info ['type'], "openid" => $openid, "access_token" => $token ['access_token'], "username" => $user_info ['nick'], "create_date" => time () );
						$members_platform = $UserPlatform->add ( $plat_data );
						if ($members_platform) { //注册成功
							$sessionInfo = array ("uid" => $userId, "name" => $user_info ['nick'], "login_type" => $user_info ['type'] );
							session ( "USER_INFO", $sessionInfo ); //保存登录的信息						
							$Members->commit (); //添加成功进行提交 
						} else { //添加失败进行回滚
							$this->error ( L('LOGON_FAILED'), __ROOT__ . "/" );
							$Members->rollback ();
						}
					
					} else {
						$this->error ( L('PRIVILEGE_GRANT_FAILED'), __ROOT__ . "/" );
					}
				}
				$jumpUrl = session ( 'jumpUrl_report' );
				if (empty ( $jumpUrl )) {
					redirect ( U ( "User/index" ) );
				} else {
					redirect ( $jumpUrl );
				}
			} else {
				$this->error ( L('FAILED_USER_INFORMATION'), __ROOT__ . "/" );
			}
		} else {
			$this->error ( L('PRIVILEGE_GRANT_FAILED'), __ROOT__ . "/" );
		}
	}

}