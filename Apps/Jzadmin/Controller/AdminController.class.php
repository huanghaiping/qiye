<?php
namespace Jzadmin\Controller;
class AdminController extends CommonController{
	
	/**
	 * 管理员列表
	 * Enter description here ...
	 */
	public function index() {
		$keyword = isset ( $_REQUEST ['keyword'] ) ? $_REQUEST ['keyword'] : "";
		$condition = "";
		if (! empty ( $keyword )) {
			$condition = " nickname like '%{$keyword}%'";
		}
		$adminRole = D ( "Admin" ); // 实例化AdminRole对象
		$roleList = $adminRole->getList ( $condition );
		$this->assign ( "keyword", $keyword );
		$this->assign ( "roleList", $roleList );
		$this->display ();
	}
	
	/**
	 * 添加管理员
	 * Enter description here ...
	 */
	public function add(){
		$roleList = D ( 'AdminRole' )->getRole ();
		$this->assign ( "info", $roleList );
		$this->display();
	}
	
	/**
	 * 保存管理员
	 * Enter description here ...
	 */
	public function saveAdd() {
		$adminRole = D ( "Admin" ); // 实例化AdminRole对象
		if (! $adminRole->create ()) {
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error ( $adminRole->getError () );
		
		} else {
			$_POST['pwd']=md5($_POST['pwd']);
			$_POST['addtime']=time();
			$result = $adminRole->add ($_POST); //添加数据 ,验证通过 可以进行其他数据操作
			if ($result) {
				$this->success ( "账号已开通，请通知相关人员", U("index") );
			} else {
				$this->error ("添加账号失败，请联系管理员" );
			}
		}
	}
	
	/**
	 * 修改管理员信息
	 * Enter description here ...
	 */
	public function edit(){
	   $id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$M = D ( 'Admin' );
		$info = $M->where ( "user_id=" . $id )->find ();
		if (empty ( $info ['user_id'] )) {
			$this->error ( "不存在该角色", U ( 'AdminRole/index' ) );
		}
		$info['pid']=$info["role_id"];
		$result = D ( 'AdminRole' )->getRole ( $info );
		$this->assign ( "info", $result );
		$this->display ();
	}
	
	/**
	 * 保存修改的管理员信息
	 * Enter description here ...
	 */
	public function saveEdit() {
		$adminRole = D ( "Admin" ); // 实例化AdminRole对象
		$validate    =    array();// 仅仅需要进行验证码的验证
		$adminRole-> setProperty("_validate",$validate);
		if (! $adminRole->create ()) {
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error ( $adminRole->getError () );
		
		} else {
			if(empty($_POST['pwd'])){ 
				unset($_POST['pwd']);
			}else{
				$_POST['pwd']=md5($_POST['pwd']);
			}
			$result = $adminRole->where("user_id='".$_POST['id']."'")->save ($_POST); //添加数据 ,验证通过 可以进行其他数据操作
			if ($result) {
				$this->success ( "成功更新", U("index") );
			} else {
				$this->error ( "更新失败，请重试" );
			}
		}
	}
	
	/**
	 * 删除管理员
	 * Enter description here ...
	 */
	public function delete(){
		
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$M = M ( 'Admin' );
		$result=$M->where("user_id='{$id}'")->delete();
		if ($result) {
				$this->success ( "删除成功", U("index") );
			} else {
				$this->error ( "删除失败，请重试" );
			}
	}

}