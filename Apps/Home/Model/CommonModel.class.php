<?php
namespace  Home\Model;
use Think\Model;
class CommonModel extends Model{
	
	public $lang = LANG_SET; //设置语言标识
	protected   $userInfo=array();				//登录后的用户信息数据
	
	
	/**
	 * +---------------------------------------------
	 * 初始化项目的开始
	 * +---------------------------------------------
	 */
	public function _initialize(){
		//判断是否有登录用户
		$userLogin = session("USER_INFO");
		if (! empty ( $userLogin )) {
			$this->userInfo = $userLogin;
		}
	}
	
}