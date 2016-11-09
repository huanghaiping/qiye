<?php
namespace Jzadmin\Controller;
class UpgradeController extends CommonController{
	
	/**
	 * +---------------------------------------------------------------
	 * 系统升级模板
	 * +---------------------------------------------------------------
	 */
	public function index(){
		$upgrade_url="http://www.qiye.com/upgrade.zip"; //升级的url
		
		$http=\Org\Net\Http::fsockopenDownload($upgrade_url);
		$this->display();
	}
}