<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {
	
	protected 	$lang = LANG_SET; 				// 当前语言标识
	protected   $categorys=array();				//系统栏目,菜单数组
	protected   $userInfo=array();				//登录后的用户信息数据
	
	/**
	 * +---------------------------------------------
	 * 初始化项目的开始
	 * +---------------------------------------------
	 */
	public function _initialize() { 
		 
		$config_info = F ( "Config_" . $this->lang, '', INCLUDE_PATH ); //导入配置文件
		if ($config_info)
			C ( $config_info );
		$this->assign ( "lang", $this->lang );
		$this->assign("lang_list",D(ADMIN_NAME.'/Lang')->getLang());
		
		//获取栏目内容
		$this->categorys = F ( "Category_" . $this->lang );
		if (!$this->categorys){
			$menu_model=D('Menu');
			$this->categorys=$menu_model->getAllMenu();
		}
		
		$this->assign("categorys",$this->categorys);
		//判断是否有登录用户
		$userLogin = session("USER_INFO");
		if (! empty ( $userLogin )) {
			$this->userInfo = $userLogin;
			$this->assign ( "userInfo", $this->userInfo ); 
		}
	}
	
	/**
	 * 获取数据的seo格式
	 * Enter description here ...
	 * @param array $info 数组信息，包含subject,summary,tags,username
	 */
	public function get_seo_info($seo_info = array()) {
		$title = empty ( $seo_info ['site_title'] ) ? C ( "SITE_TITLE" ) : $seo_info ['site_title'];
		$keywords = empty ( $seo_info ['site_keyword'] ) ? C ( "SITE_KEYWORDS" ) : $seo_info ['site_keyword'];
		$description = empty ( $seo_info ['site_description'] ) ? C ( "SITE_DESCRIPTION" ) : $seo_info ['site_description'];
		$this->assign ( 'site_title', $title."-".C ( "SITE_NAME" ) );
		$this->assign ( 'site_keywords', $keywords );
		$this->assign ( 'site_description', $description );
	}

}