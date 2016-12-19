<?php
namespace Jzadmin\Model;
class OrderModel extends CommonModel{
	
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
			foreach ( $result as $key => $value ) {
				$value ['product_info'] = $this->getProductInfoById ( $value ['goods_id'], $value ['moduleid'] );
				$catInfo = $menu_model->getMenuById ( $value ['product_info'] ['catid'] );
				$value ['url'] = createHomeUrl ($catInfo, $value ['goods_id'] );
				if (empty ( $value ['goods_attr'] )) {
					$value ['attr_list'] = unserialize ( $value ['goods_attr'] );
				}
				$result [$key] = $value;
			}
		}
		return $result;
	}
	
 
	/**
	 * +-----------------------------------------------
	 * 根据ID获取商品信息
	 * +-----------------------------------------------
	 * 
	 * @param	Int			$productId		商品ID
	 * @param	Int			$moduleId		商品模型ID
	 * @return  boolean
	 */
	public function getProductInfoById($productId, $moduleId = '') {
		$Module_model=D("Module");
		$module_info = $Module_model->getModuleIdByModuleId ( $moduleId );
		if ($module_info) {
			$model = D ( $module_info ['controller_name'] );
			$product_info = $model->field ( "id,title,catid,thumb,market_price,goods_price,promotion_price,status" )->find ( $productId );
			return $product_info;
		} else {
			return false;
		}
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
	 * +-----------------------------------------------
	 * 记录最大的订单ID
	 * +-----------------------------------------------
	 * 
	 * @return  boolean
	 */
	public function recode(){
		$order_info=$this->order("order_id desc")->field("order_id")->limit(1)->find();
		if ($order_info) {
			F("max_order_id",$order_info['order_id'],INCLUDE_PATH);
		}
	}
	
	/**
	 * +-----------------------------------------------
	 * 获取最新的订单
	 * +-----------------------------------------------
	 * 
	 * @return  boolean
	 */
	public function getNewOrder(){
		$old_order_max_id=F("max_order_id",'',INCLUDE_PATH);
		if (empty($old_order_max_id)) {
			return false;
		}
		$order_info=$this->where("order_id>".$old_order_max_id)->order("order_id desc")->field("order_id,pay_status")->select();
		if ($order_info){
			$total_order=$pay_status=0;
			foreach ($order_info as $value){
				if ($value['pay_status']==1){
					$pay_status++;
				}
				$total_order++;
			}
			return array('new_total_order'=>$total_order,"pay_success"=>$pay_status);
		}else{
			return false;
		}
	}
	
}