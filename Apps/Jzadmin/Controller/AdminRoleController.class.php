<?php
namespace Jzadmin\Controller;
class AdminRoleController extends CommonController {
	
	/**
	 * 角色列表
	 * Enter description here ...
	 */
	public function index() {
		$keyword = isset ( $_REQUEST ['keyword'] ) ? $_REQUEST ['keyword'] : "";
		$condition = "";
		if (! empty ( $keyword )) {
			$condition = " name like '%{$keyword}%'";
		}
		$adminRole = D ( "AdminRole" ); // 实例化AdminRole对象
		$roleList = $adminRole->getList ( $condition );
		$this->assign ( "keyword", $keyword );
		$this->assign ( "roleList", $roleList );
		$this->display ();
	}
	
	/**
	 * 保存管理员角色
	 * Enter description here ...
	 */
	public function saveAdd() {
		$adminRole = D ( "AdminRole" ); // 实例化AdminRole对象
		if (! $adminRole->create ()) {
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error ( $adminRole->getError () );
		
		} else {
			$result = $adminRole->addRole (); //添加数据 ,验证通过 可以进行其他数据操作
			if ($result ['status']) {
				$this->success ( $result ['info'], $result ['url'] );
			} else {
				$this->error ( $result ['info'] );
			}
		}
	}
	
	/**
	 * 保存管理员角色
	 * Enter description here ...
	 */
	public function saveEdit() {
		$adminRole = D ( "AdminRole" ); // 实例化AdminRole对象
		if (! $adminRole->create ()) {
			// 如果创建失败 表示验证没有通过 输出错误提示信息
			$this->error ( $adminRole->getError () );
		
		} else {
			$result = $adminRole->editRole (); //添加数据 ,验证通过 可以进行其他数据操作
			if ($result ['status']) {
				$this->success ( $result ['info'], $result ['url'] );
			} else {
				$this->error ( $result ['info'] );
			}
		}
	}
	
	/**
	 * 添加角色
	 * Enter description here ...
	 */
	public function add() {
		$roleList = D ( 'AdminRole' )->getRole ();
		$this->assign ( "info", $roleList );
		$this->display ();
	}
	
	/**
	 * 修改用户角色信息
	 * Enter description here ...
	 */
	public function edit() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$M = D ( 'AdminRole' );
		$info = $M->where ( "id=" . $id )->find ();
		if (empty ( $info ['id'] )) {
			$this->error ( "不存在该角色", U ( 'AdminRole/index' ) );
		}
		$result = $M->getRole ( $info );
		$this->assign ( "info", $result );
		$this->display ();
	}
	
	/**
	 * 删除角色
	 * Enter description here ...
	 */
	public function delete() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$admin_Model = M ( "Admin" );
		$is_admin_count = $admin_Model->where ( "role_id='{$id}'" )->count ();
		if ($is_admin_count > 0) {
			$this->error ( "管理员角色下有管理员,请勿删除!" );
		}
		$result = M ( "AdminRole" )->where ( "id='{$id}'" )->delete ();
		if ($result)
			$this->success ( "删除成功" );
		else
			$this->error ( "删除失败" );
	}
	
	/**
	 * 更改角色的状态
	 * Enter description here ...
	 */
	public function opRoleStatus() {
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		$staus = isset ( $_POST ['status'] ) ? intval ( $_POST ['status'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$result = D ( 'AdminRole' )->updateStatus ( $id, $staus );
		if ($result)
			$this->success ( "处理成功" );
		else
			$this->error ( "处理失败" );
	}
	
	/**
	 * 分配权限
	 * Enter description here ...
	 */
	public function changRole() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$M = M ( "AdminNode" );
		$info = M ( "AdminRole" )->where ( "id=" . ( int ) $_GET ['id'] )->find ();
		if (empty ( $info ['id'] )) {
			$this->error ( "不存在该用户组", U ( 'index' ) );
		}
		$access = M ( "AdminAccess" )->field ( "CONCAT(`node_id`,':',`level`,':',`pid`) as val" )->where ( "`role_id`=" . $info ['id'] )->select ();
		$info ['access'] = count ( $access ) > 0 ? json_encode ( $access ) : json_encode ( array () );
		$this->assign ( "info", $info );
		$datas = $M->where ( "level=1 and status=1" )->order("sort asc")->select ();
		foreach ( $datas as $k => $v ) {
			$map ['level'] = 4;
			$map ['pid'] = $v ['id'];
			$map ['status'] = 1;
			$datas [$k] ['data'] = $M->where ( $map )->select ();
			foreach ($datas [$k] ['data'] as $k4 =>$v4){
				$map ['level'] = 2;
				$map ['pid'] = $v4 ['id'];
				$map ['status'] = 1;
				$datas [$k] ['data'][$k4]['data'] = $M->where ( $map )->select ();
				foreach ( $datas [$k] ['data'][$k4]['data'] as $k1 => $v1 ) {
					$map ['level'] = 3;
					$map ['pid'] = $v1 ['id'];
					$datas [$k] ['data'][$k4]['data'] [$k1] ['data'] = $M->where ( $map )->select ();
				}	
			}
		}
		$this->assign ( "nodeList", $datas );
		$this->display ();
	}
	
	/**
	 * 保存权限信息
	 * Enter description here ...
	 */
	public function saveChangeRole() {
		$M = M ( "AdminAccess" );
		$role_id = ( int ) $_POST ['id'];
		$M->where ( "role_id=" . $role_id )->delete ();
		$data = $_POST ['data'];
		if (count ( $data ) == 0) {
			$this->error ( "清除所有权限成功", U ( "index" ) );
		}
		$datas = array ();
		foreach ( $data as $k => $v ) {
			$tem = explode ( ":", $v );
			$datas [$k] ['role_id'] = $role_id;
			$datas [$k] ['node_id'] = $tem [0];
			$datas [$k] ['level'] = $tem [1];
			$datas [$k] ['pid'] = $tem [2];
		}
		if ($M->addAll ( $datas )) {
			$this->success ( "设置成功", U ( "index" ) );
		} else {
			$this->error ( "设置失败，请重试" );
		}
	}

}