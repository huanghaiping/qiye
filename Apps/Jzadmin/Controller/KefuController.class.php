<?php
namespace Jzadmin\Controller;
class KefuController extends CommonController{
	
	/**
	 * +----------------------------------------------
	 * 	客服系统首页
	 * +----------------------------------------------
	 */
	public function index(){
		$title = isset($_REQUEST['title']) ?  addSlashesFun($_REQUEST ['title']) : "";
		$where = $param = array ();
		$sql = $sqltotla = "";
		$sql = "select * from " . C ( "DB_PREFIX" ) . "kefu  order by listorder desc, id desc ";
		$result =  $this->model_name->query ( $sql );
		if ($result){
			foreach ($result as $key=>$value){
				$value['typename']=$this->model_name->getType($value['typeid']);
				$result[$key]=$value;
			}
		}
		$this->assign ( "list", $result );
		$this->display ();
	}
	
	/**
	 * +-----------------------------------------------
	 * 添加客服
	 * +-----------------------------------------------
	 */
	Public function add() {
		if (IS_POST) {
			$result = $this->model_name->create($this->model_name->createData($_POST));
			if ($result) {
				$this->model_name->add($result);
				$this->success ( "添加成功!", U('index'));
			} else {
				$this->error ( $this->model_name->getError() );
			}
		} else {
			$this->assign("typelist",$this->model_name->getType());
			$this->assign("skinlist",$this->model_name->getSkin());
			$this->assign ( "method", "add" );
			$this->display ();
		}
	}

	/**
	 * +-----------------------------------------------
	 * 修改客服信息
	 * +-----------------------------------------------
	 */
	public function edit() {
		if (IS_POST) {
			$id=isset($_POST['id']) ? intval($_POST['id']) : "";
			$result = $this->model_name->create($this->model_name->createData($_POST));
			if ($result) {
				$this->model_name->where("id='".$id."'")->save ( $result );
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
				if ($pay_config){
					foreach ($pay_config as $key=>$value){
						$value['thumb']=$this->model_name->getSkin($value['key']);
						$pay_config[$key]=$value;
					}
				}
				$info['pay_config_param']=$pay_config;
				$ids=getSubByKey($info['pay_config_param'],"id");
				$maxId=max($ids);
				$this->assign("maxid",$maxId);
			}
			$this->assign ( "info", $info );
			$this->assign ( "method", "edit" );
			$this->assign("typelist",$this->model_name->getType());
			$this->assign("skinlist",$this->model_name->getSkin());
			$this->display ( "add" );
		}
	}
	/**
	 * +-----------------------------------------------
	 * 删除客服
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
			@unlink(".".$info['logo']);
			$this->success ( "删除成功!" );
		} else {
			$this->error ( "删除失败!" );
		}
	}
}