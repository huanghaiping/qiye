<?php
namespace Home\Model;
class CartModel extends CommonModel {
	
	protected $session_id = ''; //未登录用户初始化session
	protected $product_field = 'id,title,catid,thumb,market_price,goods_price,promotion_price,status'; //商品需要的字段信息
	

	/**
	 * +-----------------------------------------------
	 * 初始化购物车,设置Session
	 * +-----------------------------------------------
	 */
	public function _initialize() {
		parent::_initialize ();
		$this->session_id = session_id ();
	}
	
	/**
	 * +-----------------------------------------------
	 * 根据模型ID获取模型信息
	 * +-----------------------------------------------
	 * 
	 * @param	Int			$moduleId		商品模型ID
	 * @return  boolean
	 */
	public function getModuleInfoById($moduleId) {
		if (empty ( $moduleId ))
			return false;
		$module_model = D ( ADMIN_NAME . "/Module" ); //获取模型信息
		$module_info = $module_model->getModuleIdByModuleId ( $moduleId );
		return $module_info;
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
		$module_info = $this->getModuleInfoById ( $moduleId );
		if ($module_info) {
			$model = D ( $module_info ['controller_name'] );
			$product_info = $model->field ( $this->product_field )->find ( $productId );
			return $product_info;
		} else {
			return false;
		}
	}
	
	/**
	 * +-----------------------------------------------
	 * 添加购物车
	 * +-----------------------------------------------
	 * 
	 * @param	Int			$productId		商品ID
	 * @param	Int			$moduleId		商品模型ID
	 * @param   integer 	$num        	商品数量
	 * @param   array   	$spec       	规格值对应的id数组,商品属性
	 * @return  boolean
	 */
	public function addCart($productId, $catId, $num = 1, $spec = array()) {
		if (empty ( $productId ) || empty ( $catId )){
			$this->error = L("SELECT_GOODS");
			return false;
		}
		$menu_model = D ( "Menu" ); //根据栏目获取模型ID
		$menu_info = $menu_model->getMenuById ( $catId );
		if (! $menu_info) {
			
			return false;
		}
		$moduleId = $menu_info [$catId] ['typeid'];
		//获取商品信息
		$goods_info = $this->getProductInfoById ( $productId, $moduleId );
		if (! $goods_info) {
			$this->error = L("COMMODITY_DOES_NOT_EXIST");
			return false;
		}
		if ($goods_info ['status']) {
			$this->error = L("GOODS_HAVE_BEEN_OFF");
			return false;
		}
		//判断购物车里是否存在商品了.
		$cart = $this->where ( "session_id='{$this->session_id}' and goods_id='" . $productId . "' and moduleid='" . $moduleId . "' " )->find ();
		if ($cart) {
			if (empty ( $cart ['user_id'] )) {
				$cart ['user_id'] = isset ( $this->userInfo ['uid'] ) ? $this->userInfo ['uid'] : "";
			}
			$cart ['goods_number'] = $cart ['goods_number'] + $num;
			if ($goods_info ['promotion_price'] > 0) { //判断是否使用商品促销
				$cart ['total_price'] = $goods_info ['promotion_price'] * $cart ['goods_number'];
			} else {
				$cart ['total_price'] = $cart ['goods_price'] * $cart ['goods_number'];
			}
			$result = $this->save ( $cart );
		} else {
			$data = array ();
			$data ['moduleid'] = intval ( $moduleId );
			$data ['session_id'] = $this->session_id;
			$data ['goods_id'] = $productId;
			$data ['goods_name'] = isset ( $goods_info ['title'] ) ? $goods_info ['title'] : "";
			$data ['market_price'] = isset ( $goods_info ['market_price'] ) ? $goods_info ['market_price'] : 0;
			$data ['goods_price'] = isset ( $goods_info ['goods_price'] ) ? $goods_info ['goods_price'] : 0;
			$data ['goods_number'] = $num;
			$data ['promotion_price'] = isset ( $goods_info ['promotion_price'] ) ? $goods_info ['promotion_price'] : 0;
			if ($goods_info ['promotion_price'] > 0) { //判断是否使用商品促销
				$data ['total_price'] = $goods_info ['promotion_price'] * $data ['goods_number'];
			} else {
				$data ['total_price'] = $goods_info ['goods_price'] * $data ['goods_number'];
			}
			$data ['user_id'] = isset ( $this->userInfo ['uid'] ) ? $this->userInfo ['uid'] : 0;
			$data ['goods_attr'] = ! empty ( $spec ) ? serialize ( $spec ) : "";
			$data ['ctime'] = time ();
			$result = $this->add ( $data );
		}
		if ($result) {
			return $goods_info;
		} else {
			$this->error = $this->getError ();
			return false;
		}
	
	}
	
	/**
	 * +-----------------------------------------------
	 * 获取用户的购物车
	 * +-----------------------------------------------
	 */
	public function getCartList() {
		
		$cart_list = $this->where ( "session_id='" . $this->session_id . "' or user_id='" . $this->userInfo ['uid'] . "'" )->select ();
		if ($cart_list) {
			if ($cart_list) {
				$total_price = $original_price = 0;
				if ($cart_list) {
					$menu_model = D ( "Menu" );
					foreach ( $cart_list as $key => $value ) {
						$value ['product_info'] = $this->getProductInfoById ( $value ['goods_id'], $value ['moduleid'] );
						$catInfo = $menu_model->getMenuById ( $value ['product_info'] ['catid'] );
						$value ['url'] = createHomeUrl ( $catInfo [$value ['product_info'] ['catid']], $value ['goods_id'] );
						if (empty ( $value ['goods_attr'] )) {
							$value ['attr_list'] = unserialize ( $value ['goods_attr'] );
						}
						$total_price += $value ['total_price']; //计算总价
						$original_price += floatval ( $value ['goods_price'] * $value ['goods_number'] );
						$cart_list [$key] = $value;
					}
				}
				$save_price = $original_price - $total_price; //节省价
			}
			return array ('total_price' => $total_price, 'save_price' => $save_price, "cart_list" => $cart_list );
		} else {
			return false;
		}
	}
	/**
	 * +-----------------------------------------------
	 * 修改购物车数量
	 * +-----------------------------------------------
	 * 
	 * @param	Int			$cartId			购物车ID
	 * @param	Int			$goodNumber		商品数量
	 */
	public function updateCartNum($cartId, $goodNumber) {
		if (empty ( $cartId ))
			return false;
		$cart = $this->where ( "id='" . $cartId . "' and (session_id='" . $this->session_id . "' or user_id='" . $this->userInfo ['uid'] . "')" )->find ();
		if (! $cart) {
			return false;
		}
		if ($cart ['goods_number'] == $goodNumber) {
			return true;
		}
		$data = array ();
		$data ['id'] = $cartId;
		$data ['goods_number'] = $goodNumber;
		if ($cart ['promotion_price'] > 0) { //判断是否使用商品促销
			$data ['total_price'] = $cart ['promotion_price'] * $data ['goods_number'];
		} else {
			$data ['total_price'] = $cart ['goods_price'] * $data ['goods_number'];
		}
		$result = $this->save ( $data );
		return $result;
	}
	
	/**
	 * +-----------------------------------------------
	 * 删除购物车
	 * +-----------------------------------------------
	 * 
	 * @param	Int			$cartId			购物车ID
	 */
	public function clearCart($cartId) {
		if (empty ( $cartId )) {
			$result = $this->where ( "session_id='" . $this->session_id . "' or user_id='" . $this->userInfo ['uid'] . "'" )->delete (); //清空所有购物车
		} else {
			$result = $this->where ( "id='" . $cartId . "' and (session_id='" . $this->session_id . "' or user_id='" . $this->userInfo ['uid'] . "')" )->delete (); //清除单条购物车
		}
		return $result;
	}

}