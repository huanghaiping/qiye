<?php
namespace Jzadmin\Controller;
use Think\Controller;
class CommonController extends Controller {
	
	public $lang = LANG_SET;
	protected $model_name = ""; //当前模型名称
	/**
	 * 初始化项目
	 * Enter description here ...
	 */
	protected function _initialize() {
		
		$admin_node_model = D ( "AdminNode" );
		if (C ( 'USER_AUTH_ON' ) && ! in_array ( CONTROLLER_NAME, explode ( ',', C ( 'NOT_AUTH_MODULE' ) ) )) {
			$pid = $admin_node_model->where ( "name='" . CONTROLLER_NAME . "' and level=2" )->getfield ( "pid" );
			$project_name = $admin_node_model->where ( "id='" . $pid . "' and level=4" )->getfield ( "name" );
			if (! \Org\Util\Rbac::AccessDecision ( $project_name )) {
				//检查认证识别号
				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
					redirect ( U ( "Login/index" ) );//跳转到认证网关
				}
				// 没有权限 抛出错误
				if (C ( 'RBAC_ERROR_PAGE' )) {
					// 定义权限错误页面
					redirect ( C ( 'RBAC_ERROR_PAGE' ) );
				} else {
					if (C ( 'GUEST_AUTH_ON' )) {
						$this->assign ( 'jumpUrl', C ( 'USER_AUTH_GATEWAY' ) );
					}
					// 提示错误信息
					$this->error ( L ( '_VALID_ACCESS_' ) );
				}
			}
		}
		if (!in_array(CONTROLLER_NAME, array('Index','Login','Node','Content','Attachment','Sitemap'))){
			$this->model_name = D ( CONTROLLER_NAME );
		}
		$config_info = F ( "Config_" . $this->lang, '', INCLUDE_PATH ); //导入配置文件
		unset($config_info['DEFAULT_THEME']); //后台删除默认模板
		if ($config_info)
			C ( $config_info );
		$this->assign ( "position", $admin_node_model->getPosition () ); //获取当前位置的路径
		$this->assign ( "lang_list", D("Lang")->getLang() );//获取语言信息
	}
}