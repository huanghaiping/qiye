<?php
namespace Jzadmin\Controller;
class OrderController extends CommonController{
	
	/**
	 * +----------------------------------------------
	 * 查看订单列表
	 * +----------------------------------------------
	 */
	public function index(){
		$keyword = isset ( $_REQUEST ['keyword'] ) ? $_REQUEST ['keyword'] : "";
		$request ['order_status'] = isset ( $_REQUEST ['order_status'] ) ? ($_REQUEST ['order_status']) : "";
		$request ['pay_status'] = isset ( $_REQUEST ['pay_status'] ) ? ($_REQUEST ['pay_status']) : "";
		$request ['user_id'] = isset ( $_REQUEST ['user_id'] ) ? ($_REQUEST ['user_id']) : "";
		$condition = $param = array ();
		$startTime=isset($_REQUEST['startTime']) ?  $_REQUEST['startTime'] : "";
		$endTime=isset($_REQUEST['endTime']) ?  $_REQUEST['endTime'] : "";	
		if (! empty ( $keyword )) {
			$condition [] = " order_sn like '%" . $keyword . "%'";
			$param ['keyword'] = $keyword;
			$this->assign ( "keyword", $keyword );
		}
	 
		if (! empty ( $startTime )) {
			$startTime  =is_numeric($startTime) ? Date("Y-m-d H:i:s",$startTime) : $startTime;
			$time_sql=" add_time >" . strtotime($startTime) . "";
			$param ['startTime'] = strtotime($startTime);
			$this->assign ( "startTime", $startTime );
			if (! empty ( $endTime )) {
				$endTime  =is_numeric($endTime) ? Date("Y-m-d H:i:s",$endTime) : $endTime;
				$time_sql.=" and add_time < " . strtotime($endTime) . "";
				$param ['endTime'] = strtotime($endTime);
				$this->assign ( "endTime", $endTime );
			}
			$condition [] = " (".$time_sql.") ";
		}
		
		foreach ( $request as $key => $value ) {
			if ($value != "") {
				$condition [] = " {$key}='{$value}'";
				$this->assign ( $key, $value );
				$param [$key] = $value;
			}
		}
		$sql_where = "";
		if (count ( $condition ) > 0) {
			$sql_where = "where " . join ( " AND ", $condition );
		}
		$prix_name = C ( "DB_PREFIX" );
		
		$sql_count = "select * from " . $prix_name . "order " . $sql_where . " order by order_id desc ";
		$result =$this->model_name->getPageData ( $sql_count, $param, 20 );
		if ($result ['data']){
			$user_model=D("User");
			$uids=getSubByKey($result ['data'] ,"user_id");
			$user_infos=$user_model->getUserByIds($uids);
			foreach ($result ['data'] as $key=>$value){
				$value["user_info"]=array_key_exists($value['user_id'], $user_infos) ? $user_infos[$value['user_id']] : array();
				$result ['data'][$key]=$value;
			}
		}
		$this->model_name->recode(); //缓存当前最大订单ID
		$this->assign ( "orderlist", $result ['data'] );
		$this->assign ( "page", $result ['page'] );
		$this->display ();
	}
	/**
	 * +----------------------------------------------
	 * 订单详情页
	 * +----------------------------------------------
	 */
	public function detail(){
		$order_id=isset($_GET['id']) ? intval($_GET['id']) : "";
		if (empty($order_id)){
			$this->error("非法请求");
		}
		$order_info = $this->model_name->where ( "order_id='" . $order_id . "'" )->limit ( 1 )->find ();
		if (empty($order_info)){
			$this->error("非法订单");
		}
		//获取商品信息
		$shop_list=$this->model_name->getOrderGoods($order_id);
		$this->assign("shop_list",$shop_list);
		$this->assign("order_info",$order_info);
		$this->display();

	}
	/**
	 * +----------------------------------------------
	 * 设置为付款
	 * +----------------------------------------------
	 */
	public function pay(){
		$order_sn=isset($_GET['sn']) ? addSlashesFun($_GET['sn']) : "";
		if (empty($order_sn)) {
			$this->error("非法请求");
		}
		$result=$this->model_name->success($order_sn);
		if ($result) {
			$this->success("设置成功",U('index'));
		}else{
			$this->error("设置失败");
		}
	}
	
	/**
	 * +----------------------------------------------
	 * 删除订单
	 * +----------------------------------------------
	 */
	public function delorder(){
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$sql = "order_id ='{$id}'";
		if (is_array ( $id )) { 
			$id = implode(",", $id);
			$sql = "order_id in({$id})";
		}
		$value = $value == 1 ? 0 : 1;
		$result = $this->model_name->where ( $sql )->delete();
		if ($result) {	 
			$this->success ( "删除成功" );
		} else {
			$this->error ( "删除失败!" );
		}
	}
	
}