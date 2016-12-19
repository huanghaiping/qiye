<?php
namespace Home\Model;
class PayApiModel extends CommonModel {
	
	/**
	 * +-------------------------------------------
	 * 获取所有的支付方式
	 * +-------------------------------------------
	 * 
	 * @param	string	$pay_code	支付代码标识
	 */
	public function getPayList($pay_code = '') {
		$admin_pay_model = D ( ADMIN_NAME . "/PayApi" );
		$pay_list = $admin_pay_model->getPayList ();
		
		$row = array ();
		if ($pay_list) {
			foreach ( $pay_list as $value ) {
				if ($value ['status'] == 1) {
					if (isset ( $value ['config'] )) {
						$config = array ();
						foreach ( $value ['config'] as $k => $c ) {
							$config [$c ['key']] = $c ['value'];
						}
						$value ['config'] = $config;
					}
					$row [$value ['pay_code']] = $value;
				}
			}
			unset ( $pay_list );
		}
		 
		if (! empty ( $pay_code )) {
			if (! array_key_exists ( $pay_code, $row )) {
				return false;
			} else {
				return $row [$pay_code];
			}
		} else {
			return $row;
		}
	}
	
	/**
	 * +-------------------------------------------
	 * 创建支付对象
	 * ++------------------------------------------
	 * 
	 * * @param	string	$pay_type	支付代码标识
	 */
	public function createPay($pay_type) {
		//加载ThinkOauth类并实例化一个对象
		$sns = \Org\Pay\ThinkPay::getInstance ( $pay_type );
		if (! $sns) {
			$this->error = L("ILLEGAL _PAYMENT_METHOD");
			return false;
		}
		return $sns;
	}
	
	/**
	 * +-------------------------------------------
	 * 根据支付方式生成支付链接
	 * +-------------------------------------------
	 * 
	 * @param	string	$pay_type	支付代码标识
	 * @param	Array	$order		订单信息
	 * @return	String	$url	
	 */
	public function PayUrl($pay_type, $order = array()) {
		$sns = $this->createPay ( $pay_type );
		if (! $sns) {
			return false;
		}
		$pay_info = array ();
		$pay_info ["order_id"] = $order ['order_sn'];
		$pay_info ["title"] = L("ORDER_NUMBER").":" . $order ['order_sn'] . "-" . C ( 'SITE_NAME' );
		$pay_info ["total_price"] = $order ['total_price'];
		$pay_info ["url"] = '';
		$pay_info ["pay_type"] = $pay_type;
		$pay_info ['paymethod'] = "";
		$pay_info ['defaultbank'] = $pay_type;
		$pay_info ['it_b_pay'] = "24h"; //1m,24h 1分钟设置超时时间
		$pay_info ["notify_url"] = C ( 'SITE_URL' ) . "/Pay/notify_url_".$pay_type;
		$pay_info ["return_url"] = C ( 'SITE_URL' ) . "/Pay/return_url_".$pay_type;
		$url = $sns->call ( $pay_info ); //跳转到授权页面
		return $url;
	}
}