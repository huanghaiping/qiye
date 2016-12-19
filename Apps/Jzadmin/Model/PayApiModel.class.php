<?php
namespace Jzadmin\Model;
class PayApiModel extends CommonModel {
	
	protected $_validate = array (array ('pay_name', 'require', '支付接口名称不能为空', 1 ), array ('pay_code', 'require', '支付接口标识不能为空', 1 ), array ('pay_code', '', '支付接口标识已经存在！', 0, 'unique', 1 ) );
	
	/**
	 * +-----------------------------------------
	 * 登录接口模型字段
	 * +-----------------------------------------
	 * @param Array $postData	POST提交上来的数据
	 * 
	 * @return Array 返回过滤后的数组
	 */
	public function createData($postData) {
		$data = array ();
		$data ['ctime'] = time ();
		$data ['pay_name'] = isset ( $postData ['pay_name'] ) ? addSlashesFun ( $postData ['pay_name'] ) : "";
		$data ['pay_code'] = isset ( $postData ['pay_code'] ) ? addSlashesFun ( $postData ['pay_code'] ) : "";
		$data ['pay_fee'] = isset ( $postData ['pay_fee'] ) ? addSlashesFun ( $postData ['pay_fee'] ) : "";
		$data ['pay_desc'] = isset ( $postData ['pay_desc'] ) ? addSlashesFun ( $postData ['pay_desc'] ) : "";
		$data ['is_online'] = isset ( $postData ['is_online'] ) ? intval ( $postData ['is_online'] ) : "";
		$data ['status'] = isset ( $postData ['status'] ) ? intval ( $postData ['status'] ) : "";
		$data ['listorder'] = isset ( $postData ['listorder'] ) ? intval ( $postData ['listorder'] ) : "";
		$data ['is_cod'] = isset ( $postData ['is_cod'] ) ? intval ( $postData ['is_cod'] ) : "";
		$data ['id'] = isset ( $postData ['id'] ) ? intval ( $postData ['id'] ) : "";
		$data ['jumpurl'] = isset ( $postData ['jumpurl'] ) ? addSlashesFun ( $postData ['jumpurl'] ) : "";
		$pay_config_id_array = $postData ['pay_config_id'];
		if (is_array ( $pay_config_id_array )) {
			$row = array ();
			foreach ( $pay_config_id_array as $key => $value ) {
				if (! empty ( $postData ['pay_config_param_' . $value] )) {
					$row [$key] = array ('id' => $value, "key" => $postData ['pay_config_param_' . $value], "value" => $postData ['pay_config_value_' . $value] );
				}
				unset ( $postData ['pay_config_param_' . $value] );
				unset ( $postData ['pay_config_value_' . $value] );
			}
			$data ['pay_config'] = serialize ( $row );
			unset ( $pay_config_id_array );
		}
		return $data;
	}
	
	/**
	 * +-----------------------------------------
	 * 获取所有的支付方式
	 * +-----------------------------------------
	 */
	public function getPayList() {
		$pay_list = F ( "Pay_api" );
		if (! $pay_list) {
			$row = $this->field ( "id,pay_code,pay_name,pay_fee,pay_desc,pay_config,is_cod,is_online,status,jumpurl" )->order ( "listorder desc, id desc" )->select ();
			if ($row) {
				foreach ( $row as $value ) {
					$value ['config'] = unserialize ( $value ['pay_config'] );
					$pay_list [$value ['pay_code']] = $value;
				}
				F ( 'Pay_api', $pay_list );
			}
			unset ( $row );
		}
		return $pay_list;
	}
	
	/**
	 * +-----------------------------------------
	 * 根据登录接口的唯一标识获取登录的信息
	 * +-----------------------------------------
	 * @param  string $code			登录接口唯一性
	 * @return Array  $pay_info		登录的配置信息
	 */
	public function getPayByCode($code) {
		if (empty ( $code ))
			return false;
		$typeName = strtolower ( $code );
		$pay_list = $this->getPayList ();
		if ($pay_list && array_key_exists ( $typeName, $pay_list )) {
			return $pay_list [$typeName];
		} else {
			return false;
		}
	}

}