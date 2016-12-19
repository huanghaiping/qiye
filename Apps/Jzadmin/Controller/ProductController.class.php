<?php
namespace Jzadmin\Controller;

/**
 * 产品管理页面
 * @author Alan
 *
 */
class ProductController extends CommonController {
	
	private $category_list = array (); //分类的对象
	private $flag_list = array (); //推荐位的属性
	
	/**
	 * 
	 * 获取模型内容
	 */
	public function Menu($catid) {
		//获取模型
		$menu_model = D ( "Menu" );
		$this->category_list = $menu_model->getMenuByCatId ( $catid );
		$this->assign ( "category_list", $this->category_list );
		//获取推荐位
		$this->flag_list = $menu_model->getMenuFlag ();
		$this->assign ( "flag_list", $this->flag_list );
	}
	/**
	 * 产品的首页
	 */
	public function index() {
		$param = $condition = $request = array ();
		$request ['catid'] = isset ( $_REQUEST ['catid'] ) ? intval ( $_REQUEST ['catid'] ) : "";
		$request ['status'] = isset ( $_REQUEST ['status'] ) ? intval ( $_REQUEST ['status'] ) : "";
		$keyword = isset ( $_REQUEST ['keyword'] ) ? $_REQUEST ['keyword'] : "";
		$posid = isset ( $_REQUEST ['posid'] ) ? intval ( $_REQUEST ['posid'] ) : "";
		$this->Menu ( $request ['catid'] );
		$condition [] = " lang='" . $this->lang . "'";
		$param ['l'] = $this->lang;
		if (! empty ( $keyword )) {
			$condition [] = " title like '%" . $keyword . "%'";
			$param ['keyword'] = $keyword;
			$this->assign ( "keyword", $keyword );
		}
		if (! empty ( $posid )) {
			$condition [] = " posid like '%" . $posid . "%'";
			$param ['posid'] = $posid;
			$this->assign ( "posid", $posid );
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
		if ($result ['data']) {
			foreach ( $result ['data'] as $key => $value ) {
				$result ['data'] [$key] ['typename'] = array_key_exists ( $value ['catid'], $this->category_list ) ? $this->category_list [$value ['catid']] ['title'] : "";
			}
		}
		$this->assign ( "userlist", $result ['data'] );
		$this->assign ( "page", $result ['page'] );
		$this->display ();
	}
	/**
	 * 
	 * 添加产品
	 */
	public function add() {
		if (IS_POST) {
			$createData = $this->model_name->create ( $this->model_name->createData ( $_POST ) );
			if ($createData) {
				$result = $this->model_name->add ( $createData );
				if ($result) {
					$this->success ( "添加成功", U ( 'index' ) );
				} else {
					$this->error ( $this->model_name->getError () );
				}
			} else {
				$this->error ( $this->model_name->getError () );
			}
		} else {
			$this->Menu ( 0 );
			$this->assign ( "method", "add" );
			$this->display ();
		}
	}
	
	/**
	 * 
	 * 修改产品
	 */
	public function edit() {
		$idName = $this->model_name->getPk ();
		if (IS_POST) {
			$createData = $this->model_name->create ( $this->model_name->createData ( $_POST ) );
			
			if ($createData) {
				$result = $this->model_name->save ( $createData );
				if ($result) {
					$this->success ( "修改成功", U ( 'index' ) );
				} else {
					$this->error ( $this->model_name->getError () );
				}
			} else {
				$this->error ( $this->model_name->getError () );
			}
		} else {
			$id = isset ( $_GET [$idName] ) ? intval ( $_GET [$idName] ) : "";
			$info = $this->model_name->find ( $id );
			if (! $info) {
				$this->error ( "产品不存在" );
			}
			$info ['content'] = isset ( $info ['content'] ) ? stripslashes ( $info ['content'] ) : "";
			$this->assign ( "info", $info );
			$this->Menu ( $info ['catid'] );
			$this->assign ( "method", "edit" );
			$this->display ("add");
		}
	}
	
	/**
	 * 
	 * 删除产品
	 */
	public function delAll(){
		$files = isset ( $_REQUEST ['files'] ) ? $_REQUEST ['files'] : "";
		if (empty ( $files )) {
			$this->error ( "请选择要删除的产品？" );
		}
		$idStr =is_array($files) ?  implode ( ",", $files ) : $files;
		$info = $this->model_name->where ( "id in({$idStr})" )->field ( "id,content,thumb" )->select ();
		foreach ( $info as $value ) {
			$result = $this->model_name->where ( "id='" . $value ['id'] . "'" )->delete ();
			if ($result) {
				$content = stripslashes ( $value ['content'] );
				@unlink ( "." . $value ['thumb'] ); //删除缩略图 
				delContentImg ( $content ); // 删除文章内容里的图片和附件
			}
		}
		$this->success ( "删除成功!" );
	}
	
	/**
	 * 
	 * 更改产品的状态
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
}