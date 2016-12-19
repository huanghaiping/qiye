<?php
namespace Home\Controller;
class AddressController extends CommonController {
	
	private $address_model = "";
	
	/**
	 * +------------------------------------------------
	 * 初始化项目
	 * +-----------------------------------------------
	 * @see Home\Controller.CommonController::_initialize()
	 */
	public function _initialize() {
		parent::_initialize ();
		if (empty ( $this->userInfo )) {
			$this->error ( L("PLEASE_LOGIN_FIRST") );
		}
		$this->address_model = D ( "UserAddress" );
	}
	/**
	 * +------------------------------------------------
	 * 添加收货人地址
	 * +-----------------------------------------------
	 */
	public function add() {
		if (IS_POST) { 
			$result=  $this->address_model->addAddress($_POST);
			if ($result){
				$this->success(L("DEAL_WITH_SUCCESS"));
			}else{
				$this->error($this->address_model->getError());
			}
		} else {
			$user_model = D ( "User" );
			$user_info = $user_model->getUserDetailByUid ( $this->userInfo['uid'] );
			$Areas = D ( 'Area' );
			$province = $Areas->getAreaList ( 1 );
			if (! empty ( $user_info ['province'] )) {
				$city = $Areas->getAreaList ( $user_info ['province'] );
				$this->assign ( "city", $city );
			}
			if (! empty ( $user_info ['city'] )) {
				$area = $Areas->getAreaList ( $user_info ['city'] );
				$this->assign ( "area", $area );
			}
			$this->assign ( "userDetail", $user_info );
			$this->assign ( 'province', $province );
			$this->display ();
		}
	}
	/**
	 * +------------------------------------------------
	 * 修改收货人地址
	 * +-----------------------------------------------
	 */
	public function edit(){
		$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
		if (empty($id)){
			$this->error(L("ILLEGAL_REQUEST"));
		}
		if (IS_POST){
			$result=  $this->address_model->editAddress($_POST);
			if ($result){
				$this->success(L("DEAL_WITH_SUCCESS"));
			}else{
				$this->error(L('PARAMETER_ERROR').":".$this->address_model->getError());
			}
		}else{
			$info=$this->address_model->getAddressById($id);
			if ($this->userInfo ['uid'] != $info ['uid']) {
				$this->error ( L("ILLEGAL_REQUEST"));
			}
			$Areas = D ( 'Area' );
			$province = $Areas->getAreaList ( 1 );
			if (! empty ( $info ['province'] )) {
				$city = $Areas->getAreaList ( $info ['province'] );
				$this->assign ( "city", $city );
			}
			if (! empty ( $info ['city'] )) {
				$area = $Areas->getAreaList ( $info ['city'] );
				$this->assign ( "area", $area );
			}
			$this->assign ( 'province', $province );
			$this->assign("info",$info);
			$this->display();
		}
		
	}
	
	/**
	 * +------------------------------------------------
	 * 删除收货地址
	 * +------------------------------------------------
	 */
	public function del() {
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		if (empty ( $id ) || empty ( $this->userInfo )) {
			$this->error ( L("PARAMETER_ERROR"));
		}
		$info = $this->address_model->getAddressById($id);
		if ($this->userInfo ['uid'] != $info ['uid']) {
			$this->error ( L("ILLEGAL_REQUEST") );
		}
		$result = $this->address_model->where ( "id='" . $id . "'" )->delete ();
		if ($result) {
			$this->success (L("DELETE_SUCCESS") );
		} else {
			$this->error ( L("DELETE_FAILED") );
		}
	}
	
	/**
	 * 设置默认值
	 * Enter description here ...
	 */
	public function setAddress() {
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		if (empty ( $id ) || empty ( $this->userInfo )) {
			$this->error ( L("PARAMETER_ERROR") );
		}
		$info = $this->address_model->getAddressById($id);
		if ($this->userInfo ['uid'] != $info ['uid']) {
			$this->error ( L("ILLEGAL_REQUEST") );
		}
		if ($info['is_default']==1){
			$this->error(L("SET_AS_DEFAULT_ADDRESS"));
		}
		$result = $this->address_model->where ( "id='" . $id . "'" )->setField ( "is_default", 1 );
		if ($result) {
			$this->address_model->setDefault($id);
			$this->success ( L("DEAL_WITH_SUCCESS") );
		} else {
			$this->error ( L("TREATMENT_FAILURE") );
		}
	}
}
