<?php
namespace Home\Model;
class UserAddressModel extends CommonModel {
	
	/**
	 * +-----------------------------------------------
	 * 验证字段模型
	 * +-----------------------------------------------
	 * 
	 * @param	Array			$address_data		收货人地址
	 * @return  boolean
	 */
	public function createModelData($address_data) {
		if (empty ( $address_data ))
			return false;
		$data = array ();
		$data ['consignee'] = isset ( $address_data ['consignee'] ) ? addSlashesFun ( $address_data ['consignee'] ) : "";
		if (empty ( $data ['consignee'] )) {
			$this->error = L("PLEASE_SELECT_CONSIGNEE");
			return false;
		}
		$data ['province'] = isset ( $address_data ['province'] ) ? intval ( $address_data ['province'] ) : "";
		$data ['city'] = isset ( $address_data ['city'] ) ? intval ( $address_data ['city'] ) : "";
		$data ['county'] = isset ( $address_data ['county'] ) ? intval ( $address_data ['county'] ) : "";
		if (empty ( $data ['province'] ) || empty ( $data ['city'] )) {
			$this->error = L("PLEASE_SELECT_AREA");
			return false;
		}
		$data ['location'] = isset ( $address_data ['location'] ) ? addSlashesFun ( $address_data ['location'] ) : "";
		$data ['address'] = isset ( $address_data ['address'] ) ? addSlashesFun ( $address_data ['address'] ) : "";
		if (empty ( $data ['address'] ) || empty ( $data ['address'] )) {
			$this->error =L("PLEASE_FILL_DETAILS_ONSIGNEE");
			return false;
		}
		$data ['mobile'] = isset ( $address_data ['mobile'] ) ? addSlashesFun ( $address_data ['mobile'] ) : "";
		$data ['telphone'] = isset ( $address_data ['telphone'] ) ? addSlashesFun ( $address_data ['telphone'] ) : "";
		if (empty ( $data ['mobile'] )) {
			if (empty ( $data ['telphone'] )) {
				$this->error =L("PLEASE_ENTER_MOBILE");
				return false;
			}
		}
		$data ['email'] = isset ( $address_data ['email'] ) ? addSlashesFun ( $address_data ['email'] ) : "";
		$data ['ctime'] = time ();
		$data ['uid'] = $this->userInfo ['uid'];
		$data ['is_default'] = 1; //设置默认地址
		return $data;
	}
	
	/**
	 * +-----------------------------------------------
	 * 添加收货人地址
	 * +-----------------------------------------------
	 * 
	 * @param	Array			$address_data		收货人地址
	 * @return  boolean
	 */
	public function addAddress($address_data) {
		$data = $this->createModelData ( $address_data );
		if (! $data) {
			return false;
		}
		$result = $this->add ( $data );
		if ($result) {
			$this->setDefault ( $result );
		}
		return $result;
	}
	
	
	/**
	 * +-----------------------------------------------
	 * 修改收货人地址
	 * +-----------------------------------------------
	 * 
	 * @param	Array			$address_data		收货人地址
	 * @return  boolean
	 */
	public function editAddress($address_data) {
		$data = $this->createModelData ( $address_data );
		if (! $data) {
			return false;
		}
		$result = $this->where("id='".$address_data['id']."'")->save ( $data );
		if ($result) {
			$this->setDefault ( $address_data['id'] );
		}
		return $result;
	}
	/**
	 * +-----------------------------------------------
	 * 获取当前登录用户的所有收货人地址
	 * +-----------------------------------------------
	 * 
	 * @param	Array			$address_data		收货人地址
	 * @return  boolean
	 */
	public function getAddress() {
		$result = $this->where ( "uid='" . $this->userInfo ['uid'] . "'" )->order("id desc")->select ();
		return $result;
	}
	/**
	 * +-----------------------------------------------
	 * 设置默认的收货人地址
	 * +-----------------------------------------------
	 * 
	 * @param 	int 	 $id		收货地址ID
	 */
	public function setDefault($id) {
		if (empty ( $id ))
			return false;
		$result = $this->where ( "uid='" . $this->userInfo ['uid'] . "' and id !='" . $id . "' " )->setField ( "is_default", 0 );
		return $result;
	}
	
	/**
	 *+-----------------------------------------------
	 * 获取单条地址信息
	 * +-----------------------------------------------
	 * 
	 * @param int $addressId
	 */
	public function getAddressById($addressId){
		if (empty($addressId)) return false;
		$result = $this->where ( "id='" . $addressId . "'" )->find ();
		return $result;
	}
}