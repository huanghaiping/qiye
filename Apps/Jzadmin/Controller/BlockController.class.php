<?php
namespace Jzadmin\Controller;
/**
 * 碎片管理模快
 * 
 * @package      	PINGPHP
 * @author          Alan QQ:1073763462
 * @copyright     	Copyright (c) 2016  
 * @version        	PINGPHP企业网站管理系统 v1.0 2016-04-01 $
 */
class BlockController extends  CommonController{
	
	/**
	 * +-----------------------------------------------
	 * 碎片列表首页
	 * +-----------------------------------------------
	 */
	public function index() {
		$keyword = isset ( $_REQUEST ['keyword'] ) ? $_REQUEST ['keyword'] : "";
		$param ['sort'] = isset ( $_REQUEST ['sort'] ) ? $_REQUEST ['sort'] : "id"; //排序字段
		$param ['orderby'] = isset ( $_REQUEST ['orderby'] ) ? $_REQUEST ['orderby'] : "asc"; //排序方式
		$status = isset ( $_REQUEST ['status'] ) ? $_REQUEST ['status'] : "";
		$pid = isset ( $_REQUEST ['pid'] ) ? $_REQUEST ['pid'] : "";
		if (! empty ( $keyword )) {
			$condition [] = " name like '%" . $keyword . "%'";
			$param ['keyword'] = $keyword;
			$this->assign ( "keyword", $keyword );
		}
		$condition [] = " lang='" . $this->lang . "'";
		$param ['l'] = $this->lang;
		
		$sql_where = "";
		if (count ( $condition ) > 0) {
			$sql_where = "where " . join ( " AND ", $condition );
		}
		$orderby = $param ['orderby'] == "asc" ? "desc" : "asc";
		$sql = "select *  from " . C ( "DB_PREFIX" ) . "block  " . $sql_where . " order by " . $param ['sort'] . " " . $orderby . " ";
		$result = $this->model_name->getPageData ( $sql, $param, 20 );
		$this->assign ( $param ['sort'] . "_orderby", $orderby );
		$this->assign ( "userlist", $result ['data'] );
		$this->assign ( "page", $result ['page'] );
		$this->display ();
	}
	
	/**
	 * +-----------------------------------------------
	 * 
	 * 添加碎片
	 */
	public function add() {
		if (IS_POST) {
			$data = $_POST ['info'];
			if (empty ( $data ['pos'] )) {
				$this->error ( "位置标识不能为空!" );
			}
			import ( "Org.Util.Input" ); //导入过滤的类
			$data ['content'] = isset ( $data ['content'] ) ? \Input::addSlashes ( $data ['content'] ) : "";
			if ($this->model_name->where ( "pos='" . $data ['pos'] . "' and lang='{$this->lang}'" )->count () > 0) {
				$this->error ( "位置标识已经存在!" );
			}
			$data ['lang'] = $this->lang;
			if ($this->model_name->add ( $data )) {
				$this->success ( "添加成功!", U ( 'index' ) );
			} else {
				$this->error ( "添加失败!" );
			}
		} else {
			$this->assign ( "method", "add" );
			$this->display ();
		}
	}
	
	/**
	 * +--------------------------------------------------------
	 * 
	 * 修改碎片
	 * 
	 */
	public function edit() {
		if (IS_POST) {
			$data = $_POST ['info'];
			if (empty ( $data ['pos'] )) {
				$this->error ( "位置标识不能为空!" );
			}
			import ( "Org.Util.Input" ); //导入过滤的类
			$data ['content'] = isset ( $data ['content'] ) ? \Input::addSlashes ( $data ['content'] ) : "";
			$data ['lang'] = $this->lang;
			if ($this->model_name->save ( $data )) {
				$this->success ( "修改成功!", U ( 'index') );
			} else {
				$this->error ( "修改失败!" );
			}
		
		} else {
			$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
			if (empty ( $id )) {
				$this->error ( "数据请求错误" );
			}
			$info = $this->model_name->where ( "id='{$id}'" )->find ();
			if ($info){
				$info ['content'] = stripslashes ( $info ['content'] );
			}
			$this->assign ( "info", $info );
			$this->assign ( "method", "edit" );
			$this->display ( "add" );
		}
	}
	
	/**
	 * +------------------------------------------------------------
	 * 
	 * 删除碎片
	 */
	public function delete() {
		$files = isset ( $_REQUEST ['files'] ) ? $_REQUEST ['files'] : "";
		if (empty ( $files )) {
			$this->error ( "数据请求错误!" );
		}
		$idStr = is_array ( $files ) ? implode ( ",", $files ) : $files;
		$info = $this->model_name->where ( "id in({$idStr})" )->field ( "id,content" )->select ();
		foreach ( $info as $value ) {
			$result = $this->model_name->where ( "id='" . $value ['id'] . "'" )->delete ();
			if ($result) {
				$content = stripslashes ( $value ['content'] );
				delContentImg ( $content ); // 删除文章内容里的图片和附件
			}
		}
		$this->success ( "删除成功!" );
	}
	
}