<?php
namespace Home\Controller;
class CartController extends CommonController {
	
	private $cart_model = "";
	
	/**
	 * +------------------------------------------------
	 * 初始化项目
	 * +-----------------------------------------------
	 * @see Home\Controller.CommonController::_initialize()
	 */
	public function _initialize() {
		parent::_initialize ();
		$this->cart_model = D ( "Cart" );
	}
	
	/**
	 * +------------------------------------------------
	 * 购物车页面
	 * +-----------------------------------------------
	 */
	public function index() {
		$cart_list = $this->cart_model->getCartList ();
		$this->assign ( "save_price", $cart_list['save_price'] );
		$this->assign ( "total_price", $cart_list['total_price'] );
		$this->assign ( "cart_list", $cart_list['cart_list'] );
		$seoInfo = array ('site_title' => L('MY_SHOPPING_CART') );
		$this->get_seo_info ( $seoInfo );
		$this->display ();
	}
	
	/**
	 * +------------------------------------------------
	 * 添加商品进入购物车
	 * +-----------------------------------------------
	 */
	public function add() {
		$order_sn=isset($_GET['sn']) ? addSlashesFun($_GET['sn']) : "";
		if (!empty($order_sn)){
			$order_model = D ( "Order" );
			$order_info = $order_model->getOrderByOrderSn ( $order_sn );
			if ($order_info){
				$good_list=$order_model->getOrderGoods($order_info['order_id']);
				if ($good_list){
					foreach ($good_list as $key=>$value){
						$result = $this->cart_model->addCart ( $value['goods_id'],  $value['product_info']['catid'], $value['goods_number'] );
					}
				}
				if (! $result) {
					$this->error ( $this->cart_model->getError () );
				}
				$this->assign ( "cartInfo", $good_list );
			}
		}else{
			$good_id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : 0;
			$catId = isset ( $_GET ['cid'] ) ? intval ( $_GET ['cid'] ) : 0;
			$number = isset ( $_GET ['count'] ) ? intval ( $_GET ['count'] ) : 1;
			$result = $this->cart_model->addCart ( $good_id, $catId, $number );
			if (! $result) {
				$this->error ( $this->cart_model->getError () );
			}
			$good_url=createHomeUrl ( $this->categorys [$result ['catid']], $good_id );
			$good_info=array(array('url'=>$good_url,'goods_name'=>$result['title'],'product_info'=>array('thumb'=>$result['thumb']),'goods_number'=>$number));
			$this->assign ( "cartInfo", $good_info );
		}
		$seoInfo = array ('site_title' => L('ADD_CART_LIST') );
		$this->get_seo_info ( $seoInfo );
		$this->display ();
	}
	
	/**
	 * +------------------------------------------------
	 * 修改购物数量
	 * +------------------------------------------------
	 */
	public function updateCart() {
		$cartId = isset ( $_POST ['cartId'] ) ? intval ( $_POST ['cartId'] ) : "";
		$number = isset ( $_POST ['number'] ) ? intval ( $_POST ['number'] ) : 1;
		if (empty ( $cartId )) {
			$this->error ( L('ILLEGAL_REQUEST'));
		}
		if ($number <= 0) {
			$this->error ( L('COMMODITY_QUANTITY_THAN0') );
		}
		$result = $this->cart_model->updateCartNum ( $cartId, $number );
		if ($result) {
			$this->success ( L('DEAL_WITH_SUCCESS') );
		} else {
			$this->error ( L('TREATMENT_FAILURE') );
		}
	}
	
	/**
	 * +------------------------------------------------
	 * 清空购物车
	 * +------------------------------------------------
	 */
	public function clearCart() {
		$cartId = isset ( $_POST ['cartId'] ) ? intval ( $_POST ['cartId'] ) : "";
		$result = $this->cart_model->clearCart ( $cartId );
		if ($result) {
			$this->success ( L('DELETE_SUCCESS') );
		} else {
			$this->error ( L('DELETE_FAILED'));
		}
	}
}