<?php
namespace Jzadmin\Controller;
class IndexController extends CommonController {
	
	/**
	 * +----------------------------------------------------------------
	 * 后台的首页
	 * +----------------------------------------------------------------
	 */
	public function index() {
		$admin_node_model = D ( "AdminNode" );
		$this->assign ( "show_menu", $admin_node_model->show_menu () );
		$this->assign ( "show_sub_menu", json_encode ( $admin_node_model->show_sub_menu () ) );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------------
	 * 后台的主页面
	 * +----------------------------------------------------------------
	 */
	public function main() {
		//服务器信息
		if (function_exists ( 'gd_info' )) {
			$gd = gd_info ();
			$gd = $gd ['GD Version'];
		} else {
			$gd = "不支持";
		}
		$info = array ('操作系统' => PHP_OS, '主机名IP端口' => $_SERVER ['HTTP_HOST'] . ' (' . $_SERVER ['SERVER_ADDR'] . ':' . $_SERVER ['SERVER_PORT'] . ')', '运行环境' => $_SERVER ["SERVER_SOFTWARE"], 'PHP运行方式' => php_sapi_name () . "(PHP版本:" . PHP_VERSION . ")", '程序目录' => WEB_ROOT, 'MYSQL版本' => function_exists ( "mysql_close" ) ? mysql_get_client_info () : '不支持', 'GD库版本' => $gd, //            'MYSQL版本' => mysql_get_server_info(),
'上传附件限制' => ini_get ( 'upload_max_filesize' ), '执行时间限制' => ini_get ( 'max_execution_time' ) . "秒", '剩余空间' => round ( (@disk_free_space ( "." ) / (1024 * 1024)), 2 ) . 'M', '服务器时间' => date ( "Y年n月j日 H:i:s" ), '北京时间' => gmdate ( "Y年n月j日 H:i:s", time () + 8 * 3600 ), '采集函数检测' => ini_get ( 'allow_url_fopen' ) ? '支持' : '不支持', 'register_globals' => get_cfg_var ( "register_globals" ) == "1" ? "ON" : "OFF", 'magic_quotes_gpc' => (1 === get_magic_quotes_gpc ()) ? 'YES' : 'NO', 'magic_quotes_runtime' => (1 === get_magic_quotes_runtime ()) ? 'YES' : 'NO' );
		$this->assign ( 'server_info', $info );
		//获取新订单
		$order_model = D ( "Order" );
		$new_order = $order_model->getNewOrder ();
		$this->assign ( "new_order", $new_order );
		
		//获取域名授权,判断是否授权
		$domain_info = S ( "Authorize" );
		if (! $domain_info) {
			$link_model = D ( "Link" );
			$domain_post_url = $link_model->getUrl ( 6 );
			if ($domain_post_url) {
				$data = array ("siteurl" => $_SERVER ["HTTP_HOST"] );
				$result = $link_model->http ( $domain_post_url, $data );
				if ($result) {
					$domain_info = $link_model->json_to_array ( json_decode ( $result ) );
					$domain_info ["is_upgrade"] = isset ( $domain_info ["is_upgrade"] ) ? $domain_info ["is_upgrade"] : 1;
					S ( "Authorize", $domain_info, array ("expire" => 3600 ) );
				}
			}
		}
		$this->assign ( "domain_info", $domain_info );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------------
	 * 系统升级
	 * +----------------------------------------------------------------
	 */
	public function upgrade() {
		$upgrade_info=S ( "upgrade_info" );
		if ($upgrade_info){
			$this->success("可升级",$upgrade_info);
		}
		$link_model = D ( "Link" );
		$domain_post_url = $link_model->getUrl ( 7 );
		if ($domain_post_url) {
			$data = array ();
			$data ['version_id'] = C ( "SOFT_ID" );
			$data ['siteurl'] = $_SERVER ['HTTP_HOST'];
			$data ['version'] = C ( "SOFT_VERSION" );
			$data ['version_name'] = C ( "SOFT_NAME" );
			$data ['php_version'] = PHP_VERSION;
			$data ['mysql_version'] = function_exists ( "mysql_close" ) ? mysql_get_client_info () : '不支持';
			$data ['os_version'] = PHP_OS;
			$data ['server_version'] = $_SERVER ["SERVER_SOFTWARE"];
			$result = $link_model->http ( $domain_post_url, $data );
			if ($result) {
				$domain_info = $link_model->json_to_array ( json_decode ( $result ) );
				if ($domain_info&&$domain_info['status']==1){
					S ( "upgrade_info", $domain_info['data'], array ("expire" => 3600 ) );
					$this->success("可升级",$domain_info['data']);
				}else{
					$this->error($domain_info['info']);
				}
			}else{
				$this->error("服务器错误,请稍后再试");
			}
			
		}else{
			$this->error("服务器错误,请稍后再试");
		}
	}

}