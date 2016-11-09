<?php
namespace Home\Controller;
class WeixinController extends CommonController {
	
	/**
	 * +------------------------------------------------------
	 * | 获取微信的宣传页
	 * +------------------------------------------------------
	 */
	public function index() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		$wechat_page_model = D ( "WechatPage" );
		$info = $wechat_page_model->where ( "id=" . $id )->find ();
		if (empty ( $id ) || ! $info) {
			$this->error ( "无效宣传页" );
		}
		$this->assign ( "info", $info );
		$this->display ( "Wechat:page" );
	}

}