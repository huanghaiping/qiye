<?php
namespace Jzadmin\Controller;
class UserGroupController extends CommonController {
	
	/**
	 * +-----------------------------------------------
	 * 会员等级制度
	 * +-----------------------------------------------
	 */
	public function index() {
		$list = $this->model_name->where ( "lang='" . $this->lang . "'" )->order ( "sortby asc" )->select ();
		$this->assign ( "list", $list );
		F ( "user_group_" . $this->lang, $list );
		$this->display ();
	}
	
	/**
	 * +-----------------------------------------------
	 * 添加等级制度
	 * +-----------------------------------------------
	 */
	public function add() {
		if (IS_POST) {
			if (empty ( $_POST ['name'] )) {
				$this->error ( "等级名称不能为空!" );
			}
			$_POST ['lang'] = $this->lang;
			$_POST ['ctime'] = time ();
			if ($this->model_name->add ( $_POST )) {
				F ( "user_group_" . $this->lang, null );
				$this->success ( "添加成功!", U ( "index" ) );
			} else {
				$this->error ( "添加失败!" );
			}
		} else {
			$this->assign ( "method", "add" );
			$this->display ();
		}
	}
	
	/**
	 * +-----------------------------------------------
	 * 修改等级制度
	 * +-----------------------------------------------.
	 */
	public function edit() {
		if (IS_POST) {
			if (empty ( $_POST ['name'] )) {
				$this->error ( "会员组名称不能为空!" );
			}
			$_POST ['lang'] = $this->lang;
			$_POST ['ctime'] = time ();
			if ($this->model_name->save ( $_POST )) {
				F ( "user_group_" . $this->lang, null );
				$this->success ( "修改成功!", U ( "index" ) );
			} else {
				$this->error ( "修改失败!" );
			}
		} else {
			$id = $_REQUEST [$this->model_name->getPk ()];
			$vo = $this->model_name->getById ( $id );
			$this->assign ( 'info', $vo );
			$this->assign ( "method", "edit" );
			$this->display ( "add" );
		}
	}
	
	/**
	 * +-----------------------------------------------
	 * 删除等级制度
	 * +-----------------------------------------------
	 */
	public function del() {
		$id = $_REQUEST [$this->model_name->getPk ()];
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		if ($this->model_name->where ( "id={$id}" )->delete ()) {
			F ( "user_group_" . $this->lang, null );
			$this->success ( "删除成功!", U ( "index" ) );
		} else {
			$this->error ( "删除失败!" );
		}
	}
}