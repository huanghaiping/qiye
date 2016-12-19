<?php
namespace Home\Controller;
class AjaxController extends CommonController {
	
	/**
	 * +--------------------------------------------
	 * ajax 获取地区
	 * +--------------------------------------------
	 */
	public function area() {
		$area = D ( 'Area' )->getAreaList ( $_REQUEST ['areaId'] );
		$this->ajaxReturn ( $area );
	}
	
	/**
	 * +--------------------------------------------
	 * 上传用户头像
	 * +--------------------------------------------
	 */
	public function upload() {
		if (empty ( $this->userInfo )) {
			$this->ajaxReturn ( array ("status" => - 1, "info" => "您未登录,请先登录!" ) );
		}
		$uid = isset ( $_POST ['uid'] ) ? intval ( $_POST ['uid'] ) : "";
		if ($uid != $this->userInfo ['uid']) {
			$this->ajaxReturn ( array ("status" => 0, "info" => "无权上传" ) );
		}
		$adminUserModel=D(ADMIN_NAME."/User");
		$file_key='imgurl';
		$info = $adminUserModel->uploadImg ( array($file_key),'litpic' );
		if (is_array($info[$file_key])) { // 上传错误提示错误信息
			$this->ajaxReturn ( array ("status" => 0, "info" => $info[$file_key]['errorMsg'] ) );
		} else {
			$user_model = D ( 'User' );
			$user_info = $user_model->where ( "uid='{$uid}'" )->field ( "faceurl" )->limit ( 1 )->find ();
			if ($user_info&&!empty( $user_info ['faceurl'])) {
				@unlink ( "." . $user_info ['faceurl'] );
			}
			$user_model->where ( "uid='{$uid}'" )->setField ( "faceurl", $info[$file_key] );
			$this->ajaxReturn ( array ("status" => 1, "info" => "上传成功", "url" => $info[$file_key] ) );
		}
	}
	

}