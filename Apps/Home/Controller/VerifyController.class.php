<?php
namespace Home\Controller;
class VerifyController extends CommonController {
	
	/**
	 * 登录的验证码
	 * 
	 */
	public function index() {
		$imageW = isset ( $_GET ['w'] ) ? intval ( $_GET ['w'] ) : "300";
		$imageH = isset ( $_GET ['h'] ) ? intval ( $_GET ['h'] ) : 0;
		$length = isset ( $_GET ['len'] ) ? intval ( $_GET ['len'] ) : "4";
		$size = isset ( $_GET ['size'] ) ? intval ( $_GET ['size'] ) : "25";
		ob_clean (); //清除缓存
		$Verify = new \Think\Verify ( array ("imageW" => $imageW, "useCurve" => false, "length" => $length, "imageH" => $imageH, "fontSize" => $size ) );
		$Verify->entry ();
	}
	
	/**
	 * 验证验证码是否正确
	 * 
	 */
	public function checkverify($verify) {
		if (empty ( $verify ))
			return false;
		$Verify = new \Think\Verify ();
		if (! $Verify->check ( $verify )) {
			return false;
		}
		return true;
	}
}