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
	
	/**
	 * +---------------------------------------------------------------
	 * 查询关键词
	 * +---------------------------------------------------------------
	 */
	public function seachkeyword(){
		$title = isset ( $_REQUEST ['title'] ) ? addSlashesFun ( $_REQUEST ['title'] ) : "";
		$orderby= isset ( $_REQUEST ['orderby'] ) ? addSlashesFun ( $_REQUEST ['orderby'] ) : "id";
		$where = $param = array ();
		$sql = $sqltotla = "";
		if (! empty ( $title )) {
			$where [] = " keyword like '%" . $title . "%'";
			$param ['title'] = $title;
			$this->assign ( "keyword", $title );
		}
		$where [] = " lang='" . $this->lang . "'";
		$param ['l'] = $this->lang;
		if (count ( $where ) > 0) {
			$sql = " where " . join ( " AND ", $where );
			$sqltotla = join ( " AND ", $where );
		}
		
		$sql = "select id,keyword,num,ctime,jumpurl from " . C ( "DB_PREFIX" ) . "search_keyword " . $sql . " order by ".$orderby." desc ";
		$result = $this->model_name->getPageData ( $sql ,$param,20);
		$this->assign ( "page", $result ['page'] );
		$this->assign ( "list", $result ['data'] );
		$this->assign("orderby",$orderby);
		$this->display();
	}
	
	/**
	 * 关键词删除
	 * Enter description here ...
	 */
	public function delkeyword(){
		$files = isset ( $_REQUEST ['files'] ) ? $_REQUEST ['files'] : "";
		if (empty ( $files )) {
			$this->error ( "请选择要删除的关键词？" );
		}
		$idStr =is_array($files) ?  implode ( ",", $files ) : $files;
		$search_keyword_model=D("SearchKeyword");
		$info = $search_keyword_model->where ( "id in({$idStr})" )->delete ();
		if($info){
			$this->success("删除成功");
		}else{
			$this->error("删除失败");
		}
	}
	
	/**
	 * 添加关键词
	 * Enter description here ...
	 */
	public function addkeyword(){
		$search_keyword_model=D("SearchKeyword");
		if (IS_POST){
			$data=array();
			$data['keyword']=isset($_POST['keyword']) ? addSlashesFun($_POST['keyword']) : "";
			$data['num']=isset($_POST['num']) ? intval($_POST['num']) : 0;
			$data['jumpurl']=isset($_POST['jumpurl']) ? addSlashesFun($_POST['jumpurl']) : "";
			if (empty($data['keyword'])){
				$this->error("关键词名称不能为空");
			}
			$data['lang']=$this->lang;
			$data['ctime']=time();
			$is_exists=$search_keyword_model->where("keyword='".$data['keyword']."'")->count();
			if ($is_exists>0){
				$this->error("关键词已经存在");
			}
			$result=$search_keyword_model->add($data);
			if ($result){
				$this->success("添加成功",U('seachkeyword'));
			}else{
				$this->error("添加失败");
			}
		}else{
			$this->assign ( "method", "addkeyword" );
			$this->display();
		}
	}
	
	/**
	 * 修改关键词
	 * Enter description here ...
	 */
	public function editkeyword(){
		$search_keyword_model=D("SearchKeyword");
		if (IS_POST){
			$data=array();
			$data['id']=isset($_POST['id']) ? intval($_POST['id']) : 0;
			$data['keyword']=isset($_POST['keyword']) ? addSlashesFun($_POST['keyword']) : "";
			$data['num']=isset($_POST['num']) ? intval($_POST['num']) : 0;
			$data['jumpurl']=isset($_POST['jumpurl']) ? addSlashesFun($_POST['jumpurl']) : "";
			if (empty($data['keyword'])){
				$this->error("关键词名称不能为空");
			}
			$data['lang']=$this->lang;
			$data['ctime']=time();
			$result=$search_keyword_model->save($data);
			if ($result){
				$this->success("修改成功",U('seachkeyword'));
			}else{
				$this->error("修改失败");
			}
		}else{
			$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
			if (empty ( $id )) {
				$this->error ( "数据请求错误" );
			}
			$info=$search_keyword_model->where("id='".$id."'")->find();
			$this->assign("info",$info);
			$this->assign ( "method", "editkeyword" );
			$this->display("addkeyword");
		}
	}
	
}