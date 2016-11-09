<?php
namespace Jzadmin\Controller;
class LoginController extends CommonController {
	
	/**
	 * 登录的操作页面
	 * Enter description here ...
	 */
	public function index() {
		if (session (C ( 'USER_AUTH_KEY' ) )) {
			$this->error ( '已登录，正在跳转到主页', U ( 'Index/index' ) );
		}
		$this->display ();
	}
	
	/**
	 * 登录的验证码
	 * Enter description here ...
	 */
	public function verify() {
		ob_clean (); //清除缓存
		$Verify = new \Think\Verify ( array ("imageW" => "302", "useCurve" => false, "length" => 4 ) );
		$Verify->entry ();
	}
	/**
	 * 用户登出
	 * Enter description here ...
	 */
	public function logout() {
		if (isset ( $_SESSION [C ( 'USER_AUTH_KEY' )] )) {
			unset ( $_SESSION [C ( 'USER_AUTH_KEY' )] );
			unset ( $_SESSION );
			session_destroy ();
			$this->success ( '登出成功！', U ( 'Login/index' ) );
		} else {
			$this->error ( '已经登出！' );
		}
	}
	
	/**
	 * 登录的操作方法
	 * Enter description here ...
	 */
	public function checkLogin() {
		if (! empty ( $_POST )) {
			if (empty ( $_POST ['username'] )) {
				$this->error ( '用户名不能为空！' );
			} elseif (empty ( $_POST ['password'] )) {
				$this->error ( '密码不能为空！' );
			} elseif (empty ( $_POST ['verify'] )) {
				$this->error ( '验证码不能为空！' );
			}
			if (! extension_loaded ( 'curl' )) {
				$this->error ( '抱歉，您的服务器，还不支持curl扩展，请配置后登录！' );
			}
			//生成认证条件
			$map = array (); 
			// 支持使用绑定帐号登录
			if (! checkEmailFormat ( ($_POST ['username']) )) {
				$map ['nickname'] = ($_POST ['username']);
			} else {
				$map ['email'] =($_POST ['username']);
			}
			 
			$Verify = new \Think\Verify ();
			if (! $Verify->check ( $_POST ['verify'] )) {
				$this->error ( '验证码错误！', U ( 'Login/index' ) );
			}
			$authInfo = \Org\Util\Rbac::authenticate ( $map );
			 
			if (false == $authInfo) {
				$this->error ( '用户名错误！', U ( 'Login/index' ) );
			} else {
			
				if ($authInfo ['pwd'] != md5 ( I("post.password") )) {
					$this->error ( '密码错误！', U ( 'Login/index' ) );
				}
				if ($authInfo['status']==0){
					$this->error("登录失效,账号已经禁用", U ( 'Login/index' ));
				}
				$authInfo ['id'] = $authInfo ['user_id'];
				session(C ( 'USER_AUTH_KEY' ),$authInfo ['id']);
				session('loginUserName',$authInfo ['nickname']);
				session('role_id',$authInfo ['role_id']);
				if ($authInfo ['role_id'] == C ( "ADMIN_ROLE_ID" )) { //第一个用户是超级管理员
					$_SESSION [C ( 'ADMIN_AUTH_KEY' )] = true;
				}
				//保存登录信息
				$User = M ( 'Admin' );
				$data = array ("logintime" => time (),'ip'=>get_client_ip());
				$User->where ( "user_id='" . $authInfo ['user_id'] . "'" )->save ( $data );				
				// 缓存访问权限
				\Org\Util\Rbac::saveAccessList ();
				$this->success ( '登录成功！', U ( "Index/index" ) );
			
			}
		} else {
			if (session ( C ( 'USER_AUTH_KEY' ) )) {
				$this->error ( '已登录，正在跳转到主页', U ( 'Index/index' ) );
			}
			$this->display ();
		}
	}
}