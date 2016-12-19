<?php
namespace Jzadmin\Controller;
/**
 * 广告管理模快
 * 
 * @package      	PINGPHP
 * @author          Alan QQ:1073763462
 * @copyright     	Copyright (c) 2016  
 * @version        	168cm企业网站管理系统 v1.0 2016-04-01 $
 */
class LinkController extends CommonController {
	
	/**
	 * 
	 * 友链后台首页
	 */
	public function index() {
		
		$title = isset ( $_REQUEST ['title'] ) ? addSlashesFun ( $_REQUEST ['title'] ) : "";
		$where = $param = array ();
		$sql = $sqltotla = "";
		$where [] = " lang='" . $this->lang . "'";
		$param ['l'] = $this->lang;
		if (! empty ( $title )) {
			$where [] = " name like '%" . $title . "%'";
			$param ['title'] = $title;
			$this->assign ( "keyword", $title );
		}
		if (count ( $where ) > 0) {
			$sql = " where " . join ( " AND ", $where );
			$sqltotla = join ( " AND ", $where );
		}
		$sql = "select * from " . C ( "DB_PREFIX" ) . "link " . $sql . " order by id desc ";
		$result = $this->model_name->getPageData ( $sql );
		$this->assign ( "page", $result ['page'] );
		$this->assign ( "list", $result ['data'] );
		$this->display ();
	}
	
	/**
	 * 添加友链
	 * 
	 */
	Public function add() {
		if (IS_POST) {
			$result = $this->model_name->create ( $this->model_name->createData ( $_POST ) );
			if ($result) {
				$this->model_name->add ( $result );
				S ( "home_link_list", null );
				$this->success ( "添加成功!", U ( 'index' ) );
			} else {
				$this->error ( $this->model_name->getError () );
			}
		} else {
			$this->assign ( "method", "add" );
			$this->display ();
		}
	}
	
	/**
	 * 修改链接信息
	 * Enter description here ...
	 */
	public function edit() {
		if (IS_POST) {
			$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
			$result = $this->model_name->create ( $this->model_name->createData ( $_POST ) );
			if ($result) {
				$this->model_name->where ( "id='" . $id . "'" )->save ( $result );
				S ( "home_link_list", null );
				$this->success ( "修改成功!", U ( 'index' ) );
			} else {
				$this->error ( "修改失败!" );
			}
		} else {
			$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
			if (empty ( $id )) {
				$this->error ( "数据请求错误!" );
			}
			$info = $this->model_name->find ( $id );
			$this->assign ( "info", $info );
			$this->assign ( "method", "edit" );
			$this->display ( "add" );
		}
	}
	/**
	 * 删除链接
	 * Enter description here ...
	 */
	public function del() {
		
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误" );
		}
		$info = $this->model_name->where ( "id='{$id}'" )->field ( "logo" )->find ();
		if (! $info) {
			$this->error ( "友链不存在!" );
		}
		if ($this->model_name->where ( "id='{$id}'" )->delete ()) {
			@unlink ( "." . $info ['logo'] );
			S ( "home_link_list", null );
			$this->success ( "删除成功!" );
		} else {
			$this->error ( "删除失败!" );
		}
	}
	/**
	 * 
	 * 更改友链的状态
	 */
	public function updateStatus() {
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		$value = isset ( $_POST ['value'] ) ? intval ( $_POST ['value'] ) : "";
		$field = isset ( $_POST ['field'] ) ? ($_POST ['field']) : "";
		$result = $this->model_name->where ( "id='{$id}'" )->setField ( $field, $value );
		if ($result) {
			$this->success ( "处理成功!" );
		} else {
			$this->error ( "处理失败!" );
		}
	}
	/**
	 * 
	 * 获取营销链接
	 */
	public function addcommon() {
		if (IS_POST) {
			if (IS_AJAX){
				$link_info=F("CommonLink",'', INCLUDE_PATH);
				if($link_info){
					if ($link_info['is_release']==1){
						$this->error("友链已经发了,暂时不可取消");
					}
					if ($link_info['return_status']!=1){
						$this->error("友链正在审核中".$link_info['return_status']);
					}
					$link_info['is_release']=1;
					F("CommonLink",$link_info, INCLUDE_PATH);
					$service_info_url= $this->model_name->getUrl ( 4 );//发布的链接
					if (! $service_info_url) {
						$this->error ( "服务器故障,请稍后再 试" );
					}
					$param=array('linkId'=>$link_info['linkId'],'siteurl'=>$link_info['siteurl'],'token'=>$link_info['token']);
					$result=$this->model_name->http($service_info_url,$param); //更改服务器状态
					$updateStatus=$this->model_name->json_to_array ( json_decode ( $result ) );
					if ($updateStatus['status']==0){
						$this->error ( $updateStatus['info'] );
					}
					//获取所有链接
					$this->model_name->getLink($param);
					$this->success("友链发布成功");
				}else{
					$this->error("友链发布失败,请重新获取数据");
				}
				exit();
			}
			
			$data=array();
			$data['typeid']=isset($_POST['typeid']) ? intval($_POST['typeid']) : "";
			if (empty ( $data ['typeid'] )) {
				$this->error("请选择网站分类");
			}
			$data['name']=isset($_POST['name']) ? addSlashesFun($_POST['name']) : "";
			if (empty ( $data ['name'] )) {
				$this->error("网站名称不能为空");
			}
			$data ['siteurl'] = isset ( $_POST ['siteurl'] ) ? addSlashesFun ( $_POST ['siteurl'] ) : "";
			if (empty ( $data ['siteurl'] )) {
				$this->error("网站URL不能为空" );
			}
			if (! valid_host ( $data ['siteurl'] ) || (strpos($data ['siteurl'],"http://")=== false && strpos($data ['siteurl'],"https://")=== false) ) {
				$this->error ("网站URL格式错误" );
			}
		 
			$data ['contact_us'] = isset ( $_POST ['contact_us'] ) ? addSlashesFun ( $_POST ['contact_us'] ) : "";
			if (empty ( $data ['contact_us'] )) {
				$this->error("联系人不能为空" );
			}
			$data ['contact_qq'] = isset ( $_POST ['contact_qq'] ) ? addSlashesFun ( $_POST ['contact_qq'] ) : "";
			$data ['contact_tel'] = isset ( $_POST ['contact_tel'] ) ? addSlashesFun ( $_POST ['contact_tel'] ) : "";
			if (empty ( $data ['contact_qq'] ) && empty ( $data ['contact_tel'] )) {
				$this->error("联系方式不能为空" );
			}
			$data ['content'] = isset ( $_POST ['content'] ) ? addSlashesFun ( $_POST ['content'] ) : ""; //备注说明
			$data['sign'] = $this->model_name->buildRequestMysign ( $data ); //生成签名
			$data['is_release']=0;
			$data['token']=isset($_POST['token']) ? addSlashesFun($_POST['token']) : "";
			$data['linkId']=isset($_POST['linkId']) ? intval($_POST['linkId']) : "";
			//判断链接的状态
			$addType_url = $this->model_name->getUrl ( 2 ); //提交/修改链接的URL	
			if (! $addType_url) {
				$this->error ( "服务器故障,请稍后再 试" );
			}
			$result=$this->model_name->http($addType_url,$data);
			if ($result){
				$link_info = $this->model_name->json_to_array ( json_decode ( $result ) );
				if (! $link_info ['status']) {
					$this->error ( $link_info ['info'] );
				}
				$return_data=array_merge($data,$link_info['data']);
				F("CommonLink",$return_data,INCLUDE_PATH);
				$this->success($link_info['info'],U('addCommon'));
			}else{
				$this->error("URL请求错误,申请失败");
			}
		} else {
			$link_info=F("CommonLink",'', INCLUDE_PATH);
			if($link_info){
				//获取对应服务器里的信息
				$service_info_url= $this->model_name->getUrl ( 3 );//获取服务器信息
				if (! $service_info_url) {
					$this->error ( "服务器故障,请稍后再 试" );
				}
				$param=array('linkId'=>$link_info['linkId'],'siteurl'=>$link_info['siteurl']);
				$result=$this->model_name->http($service_info_url,$param);
				if ($result){
					$service_info = $this->model_name->json_to_array ( json_decode ( $result ) );
					if (! $service_info ['status']) {
						$this->error ( $service_info ['info'] );
					}
				}
				$link_info=array_merge($link_info,$service_info['data']);
				F("CommonLink",$link_info, INCLUDE_PATH);
				$this->assign("info",$link_info);
			}
			$category_url = $this->model_name->getUrl ( 1 ); //链接分类的URL
			if (! $category_url) {
				$this->error ( "服务器故障,请稍后再 试" );
			}
			$category = $this->model_name->category ('',$category_url);
			if (! $category ['status']) {
				$this->error ( $category ['info'] );
			}
			$this->assign ( "category_list", $category ['data'] );
			$this->display ();
		}
	}
}