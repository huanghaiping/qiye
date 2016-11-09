<?php
namespace Jzadmin\Controller;
/**
 * 
 * 留言板控制器
 * @author asus
 *
 */
class GuestbookController extends CommonController{

	/**
	 * 
	 * 留言板的首页
	 */
	public function index(){
		$param = $condition = $request = array ();
		$request ['status'] = isset ( $_REQUEST ['status'] ) ? intval ( $_REQUEST ['status'] ) : "";
		$keyword = isset ( $_REQUEST ['keyword'] ) ? $_REQUEST ['keyword'] : "";
		$condition [] = " lang='" . $this->lang . "'";
		$param ['l'] = $this->lang;
		if (! empty ( $keyword )) {
			$condition [] = " title like '%" . $keyword . "%'";
			$param ['keyword'] = $keyword;
			$this->assign ( "keyword", $keyword );
		}
		foreach ( $request as $key => $value ) {
			if ($value != "") {
				$condition [] = $key . " = '" . $value . "'";
				$param [$key] = $value;
				$this->assign ( $key, $value );
			}
		}
		$condition_sql = "";
		if (count ( $condition ) > 0) {
			$condition_sql = " where " . join ( " AND ", $condition );
		}
		$sql = "select * from " . C ( "DB_PREFIX" ) . strtolower ( CONTROLLER_NAME ) . $condition_sql . " order by id desc ";
		$result = $this->model_name->getPageData ( $sql, $param, 20 );
		$this->assign ( "userlist", $result ['data'] );
		$this->assign ( "page", $result ['page'] );
		$this->display ();
	}
	
	/**
	 * 
	 * 更改下载的状态
	 */
	public function updateStatus(){
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		$value = isset ( $_POST ['value'] ) ? intval ( $_POST ['value'] ) : "";
		$field=isset ( $_POST ['field'] ) ?  ( $_POST ['field'] ) : "";
		$result = $this->model_name->where ( "id='{$id}'" )->setField ( $field, $value );
		if ($result) {
			$this->success ( "处理成功!" );
		} else {
			$this->error ( "处理失败!" );
		}
	}
	
	/**
	 * 
	 * 删除留言
	 */
	public function delAll(){
		$files = isset ( $_REQUEST ['files'] ) ? $_REQUEST ['files'] : "";
		if (empty ( $files )) {
			$this->error ( "请选择要删除的留言？" );
		}
		$idStr =is_array($files) ?  implode ( ",", $files ) : $files;
		$info = $this->model_name->where ( "id in({$idStr})" )->field ( "id,content" )->select ();
		foreach ( $info as $value ) {
			$result = $this->model_name->where ( "id='" . $value ['id'] . "'" )->delete ();
			if ($result) {
				$content = stripslashes ( $value ['content'] );
				delContentImg ( $content ); // 删除下载内容里的图片和附件
			}
		}
		$this->success ( "删除成功!" );
	}
	
	
}