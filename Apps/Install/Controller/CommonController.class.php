<?php
namespace Install\Controller;
use Think\Controller;
class CommonController extends Controller{

	/**
	 * +---------------------------------------------
	 * 初始化项目的开始
	 * +---------------------------------------------
	 */
	public function _initialize() { 
		if ( is_file ( INCLUDE_PATH.'/install.lock' )) { //判断是否安装目录
			echo "已经安装过，请删除".INCLUDE_PATH."/install.lock再安装";
			exit ();
		}
		$this->assign("sys_name","网站系统");
	}
}