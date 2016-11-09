<?php
namespace Home\Controller;
class PayController extends CommonController {
	
	/**
	 * +--------------------------------------------
	 * 订单支付页面
	 * +--------------------------------------------
	 */
	public function index() {
		if (empty ( $this->userInfo )) {
			$this->error ( L("PLEASE_LOGIN_FIRST"), U ( 'Login/index', array ('jumpUrl' => base64_encode ( U ( 'Cart/index' ) ) ) ) );
		}
		//获取购物清单
		$cart_model = D ( "Cart" );
		$cart_list = $cart_model->getCartList ();
		if (empty ( $cart_list ['cart_list'] )) {
			redirect ( U ( 'Cart/index' ) );
		}
		//获取受人地址
		$userAddressModel = D ( "UserAddress" );
		$this->assign ( "address_list", $userAddressModel->getAddress () );
		//获取支付方式
		$PayApiModel = D ( "PayApi" );
		$pay_list = $PayApiModel->getPayList ();
		$this->assign ( "pay_list", $pay_list );
		
		$this->assign ( "total_price", $cart_list ['total_price'] );
		$this->assign ( "cart_list", $cart_list ['cart_list'] );
		$seoInfo = array ('site_title' => L('ORDER_PAYMENT'));
		$this->get_seo_info ( $seoInfo );
		$this->display ();
	}
	
	/**
	 * +--------------------------------------------
	 * 提交订单页面
	 * +--------------------------------------------
	 */
	public function order() {
		$consignee_id = isset ( $_POST ['consignee_id'] ) ? intval ( $_POST ['consignee_id'] ) : "";
		$pay_type = isset ( $_POST ['pay_type'] ) ? addSlashesFun ( $_POST ['pay_type'] ) : "";
		$order_mark = isset ( $_POST ['order_mark'] ) ? addSlashesFun ( $_POST ['order_mark'] ) : "";
		$order_model = D ( "Order" );
		$result = $order_model->addOrder ( $consignee_id, $pay_type, $order_mark );
		if ($result) {
			if ($result['pay_status']==1){
				$this->success(L("ORDER_SUBMISSION_SUCCESS"),U('User/order'));
			}else{
				$this->success(L("ORDER_SUBMISSION_SUCCESS"),U('Pay/payment',array('sn'=>$result['order_sn'],'pay_type'=>$pay_type)));
			}
		} else {
			$this->error ( $order_model->getError () );
		}
	}
	/**
	 * +--------------------------------------------
	 * 订单快捷支付页面
	 * +--------------------------------------------
	 */
	public function payment() {
 				
		$order_sn = isset ( $_REQUEST ['sn'] ) ? addSlashesFun ( $_REQUEST ['sn'] ) : "";
		$pay_type=isset($_REQUEST['pay_type']) ? addSlashesFun($_REQUEST['pay_type']) : "";
		$wx=isset($_REQUEST['wx']) ? ($_REQUEST['wx']) : 0;
		if (empty ( $order_sn )) {
			$this->error ( L("PARAMETER_ERROR") );
		}
		$order_model = D ( "Order" );
		$order_info = $order_model->getOrderByOrderSn ( $order_sn );
		if (! $order_info || $this->userInfo ['uid'] != $order_info ['user_id']) {
			$this->error ( L("ORDER_DOES_NOT_EXIST") );
		}
		if ($order_info ['order_status'] == 1) {
			$this->error ( L("ORDER_HAS_BEEN_CANCELLED") );
		}
		if ($order_info ['pay_status'] == 1) {
			$this->error ( L("ORDER_HAS_BEEN_PAID"));
		}
		$payApi_model = D ( "PayApi" ); 
		//获取支付方式
		$pay_list = $payApi_model->getPayList ();
		$is_weixin=is_weixin();
		$this->assign ( "pay_list", $pay_list );
		$this->assign("sn",$order_sn);
		$this->assign("pay_type",$pay_type);
		$this->assign('is_weixin',$is_weixin);
		$this->assign("wx",$wx);
		
		if (IS_POST || (!empty($pay_type)&&$is_weixin)){
			if (empty($pay_type)){
				$this->error(L("PLEASE_SELECT_PAYMENT_METHOD"));
			}
			if (!array_key_exists($pay_type, $pay_list)){
				$this->error(L("ILLEGAL _PAYMENT_METHOD"));
			}
			$pay_info=$pay_list[$pay_type];
			if (!empty($pay_info['jumpurl'])){ //跳转到另外的支付页面
				$this->success(L("BEING_PAID"),$pay_info['jumpurl']);
			}
			if (IS_POST&&!empty($pay_type)&&$is_weixin&&$wx==0){ 
				 
				$this->success(L("BEING_PAID"),U('payment',array('sn'=>$order_sn,'pay_type'=>$pay_type,"wx"=>1)));
			}
			$pay_info=array ( "order_sn" => $order_info ['order_sn'], "total_price" => $order_info ['order_amount'] );
			$pay_result = $payApi_model->PayUrl ( $pay_type, $pay_info );
			if ($pay_result&&$pay_result['status']==1){
				$order_model->updatePay($order_info ['order_sn'],$pay_type,$pay_info['pay_name']);
				if ($is_weixin&&!empty($pay_type)){
					$this->assign("paramApi",$pay_result);
				}else{
					$this->success(L("BEING_PAID"),$pay_result['url']);
				}
			}elseif($pay_result['status']==0){
				$this->error($pay_result['msg']);
			}elseif($pay_result['status']==2){
				$this->success(L("ORDER_HAS_BEEN_PAID"),$pay_result['url']);
			}else{
				$this->error($payApi_model->getError());
			}
		}
		$seoInfo = array ('site_title' => sprintf(L("ORDER_NO_PAYMENT"),$order_sn));
		$this->get_seo_info ( $seoInfo );
		$this->display();
		 
	}
	
	/**
	 * +--------------------------------------------
	 * 取消订单
	 * +--------------------------------------------
	 */
	public  function cancel(){
		$order_sn = isset ( $_REQUEST ['sn'] ) ? addSlashesFun ( $_REQUEST ['sn'] ) : "";
		if (empty ( $order_sn )) {
			$this->error ( L("ILLEGAL_REQUEST") );
		}
		$order_model = D ( "Order" );
		$order_info = $order_model->getOrderByOrderSn ( $order_sn );
		if (! $order_info || $this->userInfo ['uid'] != $order_info ['user_id']) {
			$this->error ( L("ORDER_DOES_NOT_EXIST") );
		}
		$result=$order_model->cancel($order_sn);
		if ($result){
			$this->success(L("ORDER_HAS_BEEN_CANCELLED"));
		}else{
			$this->error(L("TREATMENT_FAILURE"));
		}
	}
	/**
	 * +--------------------------------------------
	 * 删除订单
	 * +--------------------------------------------
	 */
	public  function remove(){
		$order_sn = isset ( $_REQUEST ['sn'] ) ? addSlashesFun ( $_REQUEST ['sn'] ) : "";
		if (empty ( $order_sn )) {
			$this->error ( L("PARAMETER_ERROR"));
		}
		$order_model = D ( "Order" );
		$order_info = $order_model->getOrderByOrderSn ( $order_sn );
		if (! $order_info || $this->userInfo ['uid'] != $order_info ['user_id']) {
			$this->error ( L("ORDER_DOES_NOT_EXIST") );
		}
		$result=$order_model->delorder($order_sn);
		if ($result){
			$this->success(L("ORDER_DELETE_SUCCESS"));
		}else{
			$this->error(L("ORDER_DELETION_FAILED"));
		}
	}
	
	/**
	 * +--------------------------------------------
	 * 订单支付成功
	 * +--------------------------------------------
	 */
	public function succeed() {
		
		$this->display ();
	}
	
	/**
	 * 支付宝充值服务器异步通知页面路径
	 * Enter description here ...
	 */
	public function notify_url_Alipay() {
		
		//加载ThinkOauth类并实例化一个对象
		$out_trade_no = isset ( $_POST ['out_trade_no'] ) ? $_POST ['out_trade_no'] : "";
		if (empty ( $out_trade_no ) ) { //|| empty ( $_POST )
			echo "fail";
			exit ();
		}
		$order_model = D ( "Order" );
		$order_info = $order_model->getOrderByOrderSn ( $out_trade_no );
		if (! $order_info) {
			echo "fail";
			exit ();
		}
		$pay_id = $order_info ['pay_id'];
		$sns = D ( "PayApi" )->createPay ( $pay_id );
		if (! $sns) {
			echo "fail";
			exit ();
		}
		$verify_result = $sns->verifyNotify ();
		if ($verify_result && $verify_result ['status'] == 1) { //验证正确
			$order_model->success ( $out_trade_no );
			echo "success";
		} else {
			if ($verify_result ['trade_status'] == 'TRADE_CLOSED') { //当支付失败交易在支付宝关闭后使用
			//$order_model->failure($out_trade_no); //取消交易
			}
			echo "fail";
		}
		exit ();
	}
	
	/**
	 * 支付宝充值页面跳转同步通知页面路径
	 * Enter description here ...
	 */
	public function return_url() {
		if (empty ( $this->userInfo )) {
			redirect ( U ( 'login/index' ) );
		}
		//加载ThinkOauth类并实例化一个对象
		$out_trade_no = isset ( $_GET ['out_trade_no'] ) ? $_GET ['out_trade_no'] : "";
		if (empty ( $out_trade_no ) || empty ( $_GET )) {
			$this->error ( L("ILLEGAL_REQUEST") );
		}
		
		$order_model = D ( "Order" );
		$order_info = $order_model->getOrderByOrderSn ( $out_trade_no );
		if (! $order_info || $this->userInfo ['uid'] != $order_info ['user_id']) {
			$this->error (  L("ILLEGAL_REQUEST") );
		}
		$pay_id = $order_info ['pay_id'];
		$sns = D ( "PayApi" )->createPay ( $pay_id );
		if (! $sns) {
			$this->error (  L("ILLEGAL_REQUEST") );
		}
		$verify_result = $sns->alipay_notify ();
		if ($verify_result && $verify_result ['status'] == 1) { //验证正确
			$order_model->order_success ( $out_trade_no );
			$this->error ( L("PAYMENT_SUCCESS"), U ( 'User/order' ) );
		} else {
			$this->error ( L("PAYMENT_FAILURE"), U ( 'User/order' ) );
		
		}
	
	}
	
	/**
	 * 支付宝充值服务器异步通知页面路径
	 * Enter description here ...
	 */
	public function notify_url_Wx() {
		$sns = D ( "PayApi" )->createPay ( "Wx" );
		$order_model = D ( "Order" );
		$verify_result = $sns->verifyNotify ();
		if ($verify_result && $verify_result ['status'] == 1) { //验证正确
			$order_model->success ( $verify_result['order_id'] );
			echo $verify_result['xml'];
		} else {
			echo $verify_result['xml'];
		}
		exit ();
	}
	
	/**
	 * 微信扫码支付，支付成功后自动跳转方法
	 * Enter description here ...
	 */
	public function wxorderquery(){
		$order_sn=isset($_POST['sn']) ? addSlashesFun($_POST['sn']) : "";
		if (empty($order_sn)){
			$this->error(L("PARAMETER_ERROR"));
		}
		$order_model = D ( "Order" );
		$order_info=$order_model->getOrderByOrderSn($order_sn);
		if ($order_info&&$order_info['pay_status']==1){
			$sns = D ( "PayApi" )->createPay ( "Wx" );
			$verify_result = $sns->orderQuery (array('order_id'=>$order_sn));
			if ($verify_result['status']==1){
				$this->success($verify_result['msg'],$verify_result['url']);
			}else{
				$this->error($verify_result['msg'],$verify_result['url']);
			}			
		}else{
			$this->error(L("ORDER_NOT_PAID"));
		}
	}

}