<?php
namespace Jzadmin\Controller;
/**
 * 登录接口模快
 * 
 * @package      	PINGPHP
 * @author          Alan QQ:1073763462
 * @copyright     	Copyright (c) 2016  
 * @version        	PINGPHP企业网站管理系统 v1.0 2016-04-01 $
 */
class LoginApiController extends CommonController{
	
	/**
	 * +-----------------------------------------------
	 * 登录接口列表首页
	 * +-----------------------------------------------
	 */
	public function index(){
		$title = isset($_REQUEST['title']) ?  addSlashesFun($_REQUEST ['title']) : "";
		$where = $param = array ();
		$sql = $sqltotla = "";
		$sql = "select * from " . C ( "DB_PREFIX" ) . "login_api  order by listorder desc, id desc ";
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
				F ( "Login_api", null );
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
				F ( "Login_api", null );
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
			$this->assign ( "info", $info );
			$this->assign ( "method", "edit" );
			$this->display ( "add" );
		}
	}
	/**
	 * +-----------------------------------------------
	 * 删除登录接口
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
			F ( "Login_api", null );
			$this->success ( "删除成功!" );
		} else {
			$this->error ( "删除失败!" );
		}
	}
	/**
	 * +-----------------------------------------------
	 * 邮箱设置接口
	 * +-----------------------------------------------
	 */
	public function email(){
		if (IS_POST){
			$email_config=F("EmailConfig",$_POST,INCLUDE_PATH);
			if (IS_AJAX&&!empty($_POST['ceshi'])&&checkEmailFormat($_POST['ceshi'])){ //发送测试邮件
				$verify=D("Home/Verify");
				$result=$verify->sendemail($_POST['ceshi'],"测试邮件","这是一封测试邮件",$_POST['fromusername']); 
				if ($result&&$result['status']==1){
					$this->success("发送成功");
				}else{
					$this->error("发送失败:".$result['msg']);
				}
			}
			$this->success("设置成功");
		}else{
			$info=F("EmailConfig",'',INCLUDE_PATH);
			$this->assign("info",$info);
			$this->display();
		}
	}
	
}