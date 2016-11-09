<?php
namespace Jzadmin\Controller;
/*
 * 获取消息管理
 */
class TemplateController extends CommonController {
	
	/**
	 * +------------------------------------
	 * 消息模板列表
	 * +------------------------------------
	 */
	public function index(){
		$is_del=isset($_REQUEST['id']) ? intval($_REQUEST['id']): "";
		if(!empty($is_del)){
			$this->model_name->where("id='{$is_del}'")->delete();
			F("template_cache_".$this->lang,null); //清除缓存
			$this->success ( "设置成功", U ( 'template' ) );
		}else{
			$sql = "select * from " . C ( "DB_PREFIX" ) . "template where lang='".$this->lang."'  order by id asc ";
			$result =$this->model_name->query($sql);
			$row=array();
			foreach ($result as $value){
				$row["type".$value['type']][]=$value;
			}
			unset($result);
			$this->assign ( "userlist",$row );
			$this->display();
		}
	}
	/**
	 * +------------------------------------
	 * 添加消息模板
	 * +------------------------------------
	 */
	public function add(){
		$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']): "";
		if(IS_POST){
			$_POST['lang']=$this->lang;
			if(empty($id)){
				$this->model_name->add($_POST);
			}else{
				$this->model_name->where("id='{$id}'")->save($_POST);
			}
			F("template_cache_".$this->lang,null); //清除缓存
			$this->success ( "设置成功", U ( 'index' ) );
		}else{
			if(!empty($id)){
			   $this->assign("info",$this->model_name->where("id='{$id}'")->find()); 
			}
			$this->display();
		}
	}
	/**
	 * +------------------------------------
	 * 删除消息模板
	 * +------------------------------------
	 */
	public function del(){
		$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']): "";
		if (empty($id)) {
			$this->error("非法参数");
		}
		$resutl=$this->model_name->where("id='{$id}'")->delete();
		if ($resutl) {
			F("template_cache_".$this->lang,null); //清除缓存
			$this->success ( "删除成功", U ( 'index' ) );
		}else{
			$this->error("删除失败", U ( 'index' ) );
		}
	}
	
}