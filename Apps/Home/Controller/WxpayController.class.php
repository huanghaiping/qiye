<?php
namespace Home\Controller;
/**
 * +-------------------------------------------------------
 * 微信支付
 * +-------------------------------------------------------
 * @author Alan
 *
 */
class WxpayController extends CommonController {
		
	/**
	 * +---------------------------------------------
	 * 生成微信扫描支付二维码
	 * +---------------------------------------------
	 */
	public function qrcode() {
		if (empty ( $_GET ["data"] )) {
			redirect ( U ( 'Index/index' ) );
		}
		error_reporting ( E_ERROR );
		require_once VENDOR_PATH . "Qrcode/phpqrcode.php";
		$url = urldecode ( $_GET ["data"] );
		\QRcode::png ( $url, false, QR_ECLEVEL_L, 10, 4 );
	}
}
?>