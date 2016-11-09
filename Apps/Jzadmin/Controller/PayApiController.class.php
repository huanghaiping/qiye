<?php
namespace Jzadmin\Controller;
/**
 * 支付接口模快
 * 
 * @package      	PINGPHP
 * @author          Alan QQ:1073763462
 * @copyright     	Copyright (c) 2016  
 * @version        	PINGPHP企业网站管理系统 v1.0 2016-04-01 $
 */
class PayApiController extends CommonController{
	
	/**
	 * +-----------------------------------------------
	 * 支付接口列表首页
	 * +-----------------------------------------------
	 */
	public function index(){
		$title = isset($_REQUEST['title']) ?  addSlashesFun($_REQUEST ['title']) : "";
		$where = $param = array ();
		$sql = $sqltotla = "";
		$sql = "select * from " . C ( "DB_PREFIX" ) . "pay_api  order by listorder desc, id desc ";
		$result =  $this->model_name->query ( $sql );
		$this->assign ( "list", $result );
		$this->display ();
	}
	
	/**
	 * +-----------------------------------------------
	 * 添加接口
	 * +-----------------------------------------------
	 */
	Public function add() {
		if (IS_POST) {
			$result = $this->model_name->create($this->model_name->createData($_POST));
			if ($result) {
				$this->model_name->add($result);
				F ( "Pay_api", null );
				$this->success ( "添加成功!", U('index'));
			} else {
				$this->error ( $this->model_name->getError() );
			}
		} else {
			$this->assign ( "method", "add" );
			$this->display ();
		}
	}
	
	/**
	 * +-----------------------------------------------
	 * 修改接口信息
	 * +-----------------------------------------------
	 */
	public function edit() {
		if (IS_POST) {
			$id=isset($_POST['id']) ? intval($_POST['id']) : "";
			$result = $this->model_name->create($this->model_name->createData($_POST));
			if ($result) {
				$this->model_name->where("id='".$id."'")->save ( $result );
				F ( "Pay_api", null );
				$this->success ( "修改成功!",U('index') );
			} else {
				$this->error ( "修改失败!".$this->model_name->getError() );
			}
		} else {
			$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
			if (empty ( $id )) {
				$this->error ( "数据请求错误!" );
			}
			$info = $this->model_name->find ($id);
			if (isset($info['pay_config'])&&!empty($info['pay_config'])){
				$pay_config=unserialize($info['pay_config']);
				$info['pay_config_param']=$pay_config;
				$ids=getSubByKey($info['pay_config_param'],"id");
				$maxId=max($ids);
				$this->assign("maxid",$maxId);
			}
			$this->assign ( "info", $info );
			$this->assign ( "method", "edit" );
			$this->display ( "add" );
		}
	}
	/**
	 * +-----------------------------------------------
	 * 删除支付接口
	 * +-----------------------------------------------
	 */
	public function del() {
		
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误" );
		}
		$info = $this->model_name->where ( "id='{$id}'" )->find ();
		if (! $info) {
			$this->error ( "接口不存在!" );
		}
		if ($this->model_name->where ( "id='{$id}'" )->delete ()) {
			F ( "Pay_api", null );
			$this->success ( "删除成功!" );
		} else {
			$this->error ( "删除失败!" );
		}
	}
}