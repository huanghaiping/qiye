<?php
namespace Jzadmin\Controller;

/**
 * 广告管理模快
 * 
 * @package      	PINGPHP
 * @author          Alan QQ:1073763462
 * @copyright     	Copyright (c) 2016  
 * @version        	PINGPHP企业网站管理系统 v1.0 2016-04-01 $
 */
class AdController extends CommonController {
	
	/**
	 * +----------------------------------------------
	 * 	查看广告列表
	 * +----------------------------------------------
	 */
	public function index() {
		$title = isset($_REQUEST['title']) ?  addslashes($_REQUEST ['title']) : "";
		$where = $param = array ();
		$sql = $sqltotla = "";
		$where[]=" lang='".$this->lang."'";
		$param['l']=$this->lang;
		if (! empty ( $title )) {
			$where [] = " adname like '%" . $title . "%'";
			$param ['title'] = $title;
			$this->assign ( "keyword", $title );
		}
		if (count ( $where ) > 0) {
			$sql = " where " . join ( " AND ", $where );
			$sqltotla = join ( " AND ", $where );
		}
		$sql = "select id,adname,adid,typeid,normbody,url,count,imgurl,ctime,title from " . C ( "DB_PREFIX" ) . "ad " . $sql . " order by id desc ";
		$result =$this->model_name->getPageData ( $sql );
		$this->assign ( "page", $result ['page'] );
		$this->assign ( "list", $result ['data'] );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------
	 * 	添加广告
	 * +----------------------------------------------
	 */
	public function addAd() {
		if (IS_POST) {
			$create_data=$this->model_name->create($this->model_name->createData($_POST));
			if ($create_data){
				$result=$this->model_name->add($create_data);
				if ($result) {
					$this->success ( "添加成功!",U('index',array('l'=>$this->lang)) );
				} else {
					$this->error ( "添加失败" );
				}
			}else{
				$this->error($this->model_name->getError());
			}
		} else {
			$this->display ();
		}
	}
	
	/**
	 * +----------------------------------------------
	 * 	修改广告
	 * +----------------------------------------------
	 */
	public function edit() {
		if (IS_POST) {
			$id = isset($_POST ['id']) ? intval ( $_POST ['id'] ) : "";
			$create_data=$this->model_name->create($this->model_name->createData($_POST));
			if ($create_data){
				$result=$this->model_name->where("id='".$id."'")->save($create_data);
				if ($result) {
					$this->success ( "修改成功!",U('index',array('l'=>$this->lang)) );
				} else {
					$this->error ( "修改失败" );
				}
			}else{
				$this->error($this->model_name->getError());
			} 
		} else {
			$id = isset($_GET ['id']) ? intval ( $_GET ['id'] ) : "";
			if (empty($id)){
				$this->error("非法请求");
			}
			$result = $this->model_name->find ( $id );
			$typeid = $result ['typeid'];
			if ($typeid == "1") {
				$normbody = $result ['normbody'];
				$result ["typename"] = "代码";
			}
			if ($typeid == "2") {
				$normbody = $result ['normbody'];
				$result ["typename"] = "文字";
			}
			if ($typeid == "3") {
				$normbody = explode ( ",", $result ['normbody'] );
				$result ["width"] = $normbody [0];
				$result ["height"] = $normbody [1];
				$result ["istitle"] = $normbody [2];
				$result ["typename"] = "图片";
			}
			if ($typeid == "4") {
				$normbody = explode ( ",", $result ['normbody'] );
				$result ["width"] = $normbody [0];
				$result ["height"] = $normbody [1];
				$result ["typename"] = "flash";
			}
			if ($typeid == "5") {
				$normbody = explode ( ",", $result ['normbody'] );
				$result ["width"] = $normbody [0];
				$result ["height"] = $normbody [1];
				$result ["typename"] = "幻灯片";
			}
			$result['normbody']=isset($result['normbody']) ? stripslashes($result['normbody']) : "";
			$this->assign ( "list", $result );
			$this->display ();
		}
	}
	
	/**
	 * +----------------------------------------------
	 * 	删除广告
	 * +----------------------------------------------
	 */
	public function del() {
		$id = isset($_GET ['id']) ? intval ( $_GET ['id'] ) : "";
		if (empty($id)){
			$this->error("非法请求");
		}
		$result = $this->model_name->find ( $id );
		$typeid = $result ['typeid'];
		if (! empty ( $result ['imgurl'] )) {
			@unlink ( "." . $result ['imgurl'] );
		}
		$result = $this->model_name->where("id='".$id."'")->delete ( );
		if ($result) {
			if ($typeid == 5) { //删除幻灯片
				$sql = "select id,picurl from " . C ( "DB_PREFIX" ) . "slide where typeid=" . $typeid;
				$delInfo = $this->model_name->query ( $sql );
				foreach ( $delInfo as $value ) {
					$sql = "delete from " . C ( "DB_PREFIX" ) . "slide where id=" . $value ['id'];
					if ($this->model_name->execute ( $sql )) {
						@unlink ( "." . $value ['picurl'] );
					}
				}
			}
			$this->success ( "删除成功!", U('index') );
		} else {
			$this->error ( "删除失败" );
		}
	}
	
	/**
	 * +----------------------------------------------
	 * 	幻灯片广告管理
	 * +----------------------------------------------
	 */
	public function slidelist() {
		$typeid = isset( $_GET ['id']) ? intval ( $_GET ['id'] ) : "";
		if (empty($typeid)){
			$this->error("非法请求");
		}
		$slide_model=D("Slide");
		$field=" id,title,linkurl,picurl,ctime,width,height,sortslide ";
		$result = $slide_model->where("typeid=" . $typeid . "")->field($field)->order("id desc")->select();
		$this->assign ( "typeid", $typeid );
		$this->assign ( "listslde", $result );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------
	 * 	上传幻灯片广告
	 * +----------------------------------------------
	 */
	public function addslide() {
		if (IS_POST) {
			$slide_model=D("Slide");
			$create_data=$this->model_name->createDataBySlide($_POST);
			if ($create_data){
				$create_data=$slide_model->create($create_data);
				$result=$slide_model->add($create_data);
				if ($result) {
					$this->success ( "添加成功!",U('slideList',array('id'=>$create_data ['typeid'])) );
				} else {
					$this->error ( "添加失败" );
				}
			}else{
				$this->error($this->model_name->getError());
			} 
		} else {
			$typeid = intval ( $_GET ['typeid'] ); 
			$this->assign ( "typeid", $typeid );
			$this->display ();
		}
	}
	
	/**
	 * +----------------------------------------------
	 * 	修改幻灯片广告
	 * +----------------------------------------------
	 */
	public function editslide() {
		$slide_model=D("Slide");
		if (IS_POST) {
			$id = isset($_POST ['id']) ? intval ( $_POST ['id'] ) : "";
			$create_data=$this->model_name->createDataBySlide($_POST);
			if ($create_data){
				$create_data=$slide_model->create($create_data);
				$result=$slide_model->where("id='".$id."'")->save($create_data);
				if ($result) {
					$this->success ( "修改成功!",U('slideList',array('id'=>$create_data['typeid'])) );
				} else {
					$this->error ( "修改失败:" .$slide_model->getError());
				}
			}else{
				$this->error($this->model_name->getError());
			}  
		} else {
			$id = isset($_GET ['id']) ? intval ( $_GET ['id'] ) : "";
			if (empty($id)){
				$this->error("非法请求");
			}
			$result = $slide_model->find ( $id );
			$this->assign ( "list", $result);
			$this->display ();
		}
	}
	
	/**
	 * +----------------------------------------------
	 * 	删除幻灯片广告
	 * +----------------------------------------------
	 */
	public function delslide() {
		$id = isset($_GET ['id']) ? intval ( $_GET ['id'] ) : "";
		if (empty($id)){
			$this->error("非法请求");
		}
		$sql = "select picurl from " . C ( "DB_PREFIX" ) . "slide where id=" . $id . " limit 1";
		$slide_model=D("Slide");
		$result = $slide_model->where("id='".$id."'")->find($id);
		if ($result) {
			if (! empty ( $result ['picurl'] )) {
				@unlink ( "." . $result ['picurl'] );
			}
			$slide_model->where("id='".$id."'")->delete ();
			$this->success ( "删除成功!",U('slideList',array('id'=>$result['typeid'])) );
		} else {
			$this->error ( "删除失败" );
		}
	
	}
}