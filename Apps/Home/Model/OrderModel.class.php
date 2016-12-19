<?php
namespace Home\Model;
/**
 * +-----------------------------------------------
 * 订单管理页面
 * +-----------------------------------------------
 * @author Alan
 *
 */
class OrderModel extends CommonModel {
	
	/**
	 * +---------------------------------------
	 * 添加订单
	 * +---------------------------------------
	 * 
	 * @param 	int 		$ consignee_id
	 * @param 	string 	 $pay_type
	 * @param 	string	 $order_mark
	 */
	public function addOrder($consignee_id, $pay_type, $order_mark) {
		if (empty ( $consignee_id )) {
			$this->error = L("PLEASE_SELECT_CONSIGNEE");
			return false;
		}
		if (empty ( $pay_type )) {
			$this->error = L("PLEASE_SELECT_PAYMENT_METHOD");
			return false;
		}
		//获取单条收货人信息
		$UserAddressModel = D ( "UserAddress" );
		$address_info = $UserAddressModel->getAddressById ( $consignee_id );
		if (! $address_info || $address_info ['uid'] != $this->userInfo ['uid']) {
			$this->error = L("PLEASE_SELECT_CONSIGNEE");
			return false;
		}
		//选择支付方式
		$PayApi = D ( "PayApi" );
		$pay_info = $PayApi->getPayList ( $pay_type );
		if (! $pay_info) {
			$this->error = L("PLEASE_SELECT_PAYMENT_METHOD");
			return false;
		}
		$data = array ();
		$data ['order_sn'] = $this->get_order_sn ();
		$data ['user_id'] = $this->userInfo ['uid'];
		$data ['order_status'] = $data ['pay_status'] = 0;
		$data ['consignee'] = $address_info ['consignee'];
		$data ['location'] = $address_info ['location'];
		$data ['province'] = $address_info ['province'];
		$data ['city'] = $address_info ['city'];
		$data ['district'] = $address_info ['county'];
		$data ['address'] = $address_info ['address'];
		
		$data ['tel'] = $address_info ['telphone'];
		$data ['mobile'] = $address_info ['mobile'];
		$data ['email'] = $address_info ['email'];
		$data ['pay_id'] = $pay_type;
		$data ['pay_name'] = $pay_info ['pay_name'];
		$data ['pay_fee'] = $pay_info ['pay_fee'];
		//获取购物车信息
		$cart_model = D ( "Cart" );
		$cart_list = $cart_model->getCartList ();
		if (!$cart_list){
			$this->error=L("SHOPPING_CART_IS_EMPTY");
			return false;
		}
		$data ['goods_amount'] = count ( $cart_list ['cart_list'] );
		$data ['order_amount'] = $cart_list ['total_price'];
		$data ['order_mark'] = $order_mark;
		$data ['add_time'] = time ();
		$data ['lang'] = $this->lang;
		if ($data ['order_amount']<=0){
			$data ['pay_status']=1;
			$data ['pay_name']="无";
			$data ['pay_time']=time();
		}else{
			$data ['pay_status']=0;
		}
		$order_id = $this->add ( $data );
		if ($order_id) {
			$order_goods_model = D ( "OrderGoods" );
			$good_info = array ();
			$good_info ['order_id'] = $order_id;
			foreach ( $cart_list ['cart_list'] as $value ) {
				$good_info ['goods_id'] = $value ['goods_id'];
				$good_info ['moduleid'] = $value ['moduleid'];
				$good_info ['goods_name'] = $value ['goods_name'];
				$good_info ['goods_number'] = $value ['goods_number'];
				$good_info ['market_price'] = $value ['market_price'];
				$good_info ['goods_price'] = $value ['goods_price'];
				$good_info ['promotion_price'] = $value ['promotion_price'];
				$good_info ['total_price'] = $value ['total_price'];
				$good_info ['goods_attr'] = $value ['goods_attr'];
				$order_goods_model->add ( $good_info );
			}
			//清空购物车
			$cart_model->clearCart ();
			return array ('id' => $order_id, "order_sn" => $data ['order_sn'], "total_price" => $cart_list ['total_price'],"pay_status"=>$data ['pay_status'] );
		} else {
			return false;
		}
	}
	
	/**
	 * +---------------------------------------
	 * 得到新订单号
	 * +---------------------------------------
	 * @return  string
	 */
	public function get_order_sn() {
		/* 选择一个随机的方案 */
		mt_srand ( ( double ) microtime () * 1000000 );
		return date ( 'Ymd' ) . str_pad ( mt_rand ( 1, 99999 ), 5, '0', STR_PAD_LEFT );
	}
	
	/**
	 * +---------------------------------------
	 * 根据订单ID获取商品信息
	 * +---------------------------------------
	 * 
	 * @param	int		$orderid		订单编号
	 * @return  Array
	 */
	public function getOrderGoods($orderid) {
		if (empty ( $orderid ))
			return false;
		$order_goods_model = D ( "OrderGoods" );
		$result = $order_goods_model->where ( "order_id='" . $orderid . "'" )->order ( "id desc" )->select ();
		if ($result) {
			$menu_model = D ( "Menu" );
			$cart_model = D ( "Cart" );
			foreach ( $result as $key => $value ) {
				$value ['product_info'] = $cart_model->getProductInfoById ( $value ['goods_id'], $value ['moduleid'] );
				$catInfo = $menu_model->getMenuById ( $value ['product_info'] ['catid'] );
				$value ['url'] = createHomeUrl ( $catInfo [$value ['product_info'] ['catid']], $value ['goods_id'] );
				if (empty ( $value ['goods_attr'] )) {
					$value ['attr_list'] = unserialize ( $value ['goods_attr'] );
				}
				$result [$key] = $value;
			}
		}
		return $result;
	}
	
	/**
	 * +---------------------------------------
	 * 根据订单ID获取订单信息
	 * +---------------------------------------
	 * 
	 * @param	int		$order_sn		订单编号
	 * @return  Array
	 */
	public function getOrderByOrderSn($order_sn) {
		if (empty ( $order_sn ))
			return false;
		$info = $this->where ( "order_sn='" . $order_sn . "'" )->limit ( 1 )->find ();
		return $info;
	}
	
	/**
	 * +---------------------------------------
	 * 支付成功后修改订单状态
	 * +---------------------------------------
	 * 
	 * @param	int		$order_sn		订单编号
	 * @return  Array
	 */
	public function success($order_sn) {
		if (empty ( $order_sn ))
			return false;
		$field = "order_id,order_sn,user_id,order_status,pay_status,order_amount,goods_amount,email,consignee,location,address,pay_name";
		$order_info = $this->where ( "order_sn='{$order_sn}'" )->field ( $field )->limit ( 1 )->find ();
		if ($order_info) {
			if ($order_info ['pay_status'] == 0) {
				if ($order_info ['order_status'] == 0) { //订单已经取消0 正常，1取消，2 删除
					$data = array ('order_status' => 0, "pay_status" => 1, "pay_time" => time () );
					$result = $this->where ( "order_id='" . $order_info ['order_id'] . "'" )->save ( $data );
					if ($result) { //更改订单状态成功
						//发送邮件
						if (! empty ( $order_info ['email'] ) && checkEmailFormat ( $order_info ['email'] )) {
							$verify_model = D ( "Verify" );
							$send_info = array ('email' => $order_info ['email'], 'name' => $order_info ['consignee'], 'pay_time' => time (), 'order_sn' => $order_sn, 'stiename' => C ( 'SITE_NAME' ), 'total_price' => $order_info ['order_amount'], 'address' => $order_info ['location'] . " " . $order_info ["address"], 'pay_name' => $order_info ['pay_name'], 'url' => C ( 'SITE_URL' ) );
							$verify_model->sendmail ( "SEND_EMAIL_ORDER_USER", $send_info );
						}
						return true;
					} else {
						return false;
					}
				} else {
					return false;
				}
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * +---------------------------------------
	 * 支付失败后修改订单状态
	 * +---------------------------------------
	 * 
	 * @param	int		$order_sn		订单编号
	 * @return  Array
	 */
	public function failure($order_sn) {
		if (empty ( $order_sn ))
			return false;
		$result = $this->where ( "order_sn='{$order_sn}'" )->save ( array ('order_status' => 1 ) );
		return $result;
	}
	
	/**
	 * +---------------------------------------
	 * 订单取消后修改订单状态
	 * +---------------------------------------
	 * 
	 * @param	int		$order_sn		订单编号
	 * @param	string	$reason			取消订单理由
	 * @return  Array
	 */
	public function cancel($order_sn, $reason = '') {
		if (empty ( $order_sn ))
			return false;
		$result = $this->where ( "order_sn='{$order_sn}'" )->save ( array ('order_status' => 1,'cancel_time'=>time(), 'reason' => $reason ) );
		return $result;
	}
	
	/**
	 * +---------------------------------------
	 * 删除
	 * +---------------------------------------
	 * 
	 * @param	int		$order_sn		订单编号
	 * @return  Array
	 */
	public function delorder($order_sn) {
		if (empty ( $order_sn ))
			return false;
		$result = $this->where ( "order_sn='{$order_sn}'" )->save ( array ('order_status' => 2) );
		return $result;
	}
	
	/**
	 * +---------------------------------------
	 * 更改订单的支付方式
	 * +---------------------------------------
	 * 
	 * @param string $order_sn	订单号
	 * @param string $pay_type	支付标识
	 * @param string $pay_name  支付名称
	 */
	public function updatePay($order_sn, $pay_type, $pay_name) {
		if (empty ( $order_sn ))
			return false;
		$result = $this->where ( "order_sn='{$order_sn}'" )->save ( array ('pay_id' => $pay_type, 'pay_name' => $pay_name ) );
		return $result;
	}
	
	/**
	 * +---------------------------------------
	 * 修改订单号，当支付超时的时候重新发生请求
	 * +---------------------------------------
	 * 
	 * @param string $old_order_sn	旧订单号
	 */
	public function updateOrderSn($old_order_sn){
		if (empty ( $old_order_sn ))
			return false;
		$new_order_sn=$this->get_order_sn();
		$result = $this->where ( "order_sn='{$old_order_sn}'" )->save ( array ('order_sn' => $new_order_sn ));
		return $new_order_sn;
	}

}