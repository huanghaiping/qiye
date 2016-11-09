<?php
namespace Jzadmin\Controller;
/**
 * 
 *	模型控制页面
 * @author Alan
 *
 */
class MenuController extends  CommonController{
		
	/**
	 * 模型控制的首页
	 */
	public function index(){
		$temp = $this->model_name->getCategory (array("lang"=>$this->lang)); 
		if ($temp){
			$module_model_name=D("Module");
			$moduleIds=$module_model_name->getModuleIdByModuleIds(getSubByKey($temp,"typeid"));
			foreach ($temp as $key=>$value){
				$value['module_name']=array_key_exists($value['typeid'], $moduleIds) ? $moduleIds[$value['typeid']]:array();
				$value ['url'] = createHomeUrl ( $value );
				$temp[$key]=$value;
			}
		}
		$this->assign ( "list", $temp );
		$this->display ();
	}
	
	/**
	 * 添加菜单
	 */
	public function  add(){
		if (IS_AJAX){
			$model_name=isset($_POST['controllerName']) ? addSlashesFun($_POST['controllerName']) : "Article";
			$data=array('status'=>0);
			if (!empty($model_name)){
				$templates= template_file($model_name);
				$data=array('status'=>1,'data'=>$templates);
				$this->ajaxReturn($data,"JSON");
			}
			$this->ajaxReturn($data,"JSON");
		}
		if (IS_POST){
			$_POST ['model_name'] = isset($_POST ['model_name']) ?  ucwords ( $_POST ['model_name'] ) : "";
			//上传缩略图
			$thumb_data=$this->model_name->uploadImg(array('thumb'),'images');
			$_POST ['thumb']=$thumb_data['thumb'];
			import ( "Org.Util.Input" ); //导入过滤的类
			$_POST ['lang']=$this->lang;
			$_POST ['ctime'] = time ();
			$_POST ['listtype']=isset($_POST['listtype']) ? intval($_POST['listtype']) : 0;
			$createData=$this->model_name->create($_POST);
			if ($createData){
				$result=$this->model_name->add($createData);
				if ($result){
					$this->model_name->updateCache();
					if (!empty($_POST ['content'])){
						$menu_info_model=D("MenuInfo");
						$content = isset ( $_POST ['content'] ) ? \Input::addSlashes ( $_POST ['content'] ) : "";
						$menu_info_model->add(array('menu_id'=>$result,'content'=>$content));
					}
					
					$this->success("添加成功",U('index'));
				}else{
					$this->error($this->model_name->getError());
				}
			}else{
				$this->error($this->model_name->getError());
			}
		}else{
			$fid=isset($_GET['fid']) ? intval($_GET['fid']):"";
			//获取所有栏目
			$temp = $this->model_name->getCategory (array("lang"=>$this->lang));
			$this->assign ( "list", $temp );
			//获取模型类型
			$module_model=D("Module");
			$this->assign("module_list",$module_model->getModule());
			$this->assign("fid",$fid);
			$this->assign("method","add");
			$this->display();
		}
	}
	
	/**
	 * 修改菜单
	 */
	public function edit(){
		if (IS_POST){
			$_POST ['model_name'] = isset($_POST ['model_name']) ?  ucwords ( $_POST ['model_name'] ) : "";
			$thumb_data=$this->model_name->uploadImg(array('thumb'),'images');
			$_POST ['thumb']=$thumb_data['thumb'];
			import ( "Org.Util.Input" ); //导入过滤的类
			$_POST['lang']=$this->lang;
			$_POST ['ctime'] = time ();
			$_POST ['listtype']=isset($_POST['listtype']) ? intval($_POST['listtype']) : 0;
			$createData=$this->model_name->create($_POST);
			if ($createData){
				$result=$this->model_name->save($createData); 
				if ($result){
					$this->model_name->updateCache();
					$menu_info_model=D("MenuInfo");
					$menu_info_model->where('menu_id='.$_POST['id'])->delete();
					if (!empty($_POST ['content'])){ 
						$content = isset ( $_POST ['content'] ) ? \Input::addSlashes ( $_POST ['content'] ) : "";
						$menu_info_model->add(array('menu_id'=>$_POST['id'],'content'=>$content));
					}
					$this->success("修改成功",U('index'));
				}else{
					$this->error($this->model_name->getError());
				}
			}else{
				$this->error($this->model_name->getError());
			}
		}else{
			$id=isset($_GET['id']) ? intval($_GET['id']) : "";
			if (empty($id))
				$this->error("非法请求");
			$info=$this->model_name->where("id='".$id."'")->find();
			if (!$info)
				$this->error("非法请求");
			
			$menu_info_model=D("MenuInfo");
			$content=$menu_info_model->where("menu_id=".$id)->find();
			if ($content){
				$info['content']=isset($content['content']) ? stripslashes($content['content']) : "";
			}
			$this->assign("info",$info);
			//获取所有栏目
			$temp = $this->model_name->getCategory (array("lang"=>$this->lang));
			$this->assign ( "list", $temp );
			//获取模型类型
			$module_model=D("Module");
			$this->assign("module_list",$module_model->getModule());
			
			$this->assign("method","edit");
			$this->display("add");
		}
	}
	
	/**
	 * 
	 * 删除菜单
	 */
	public function del(){
		$id=isset($_GET['id']) ? intval($_GET['id']) : "";
		if (empty($id))
			$this->error("非法请求");
		$info=$this->model_name->where("id='".$id."'")->find();
		if (!$info)
			$this->error("非法请求");
		
		$result = $this->model_name->where ( "id=" . $id )->delete ();
		if ($result) {
			$menu_info_model=D("MenuInfo");
			$content=$menu_info_model->where('menu_id='.$id)->find();
			if ($content){
				$menu_info_model->where('menu_id='.$id)->delete();
				$info['content']=isset($content['content']) ? stripslashes($content['content']) : "";
				delContentImg ( $info['content'] ); // 删除文章内容里的图片和附件
			}
			@unlink ( ".." . $info ['imgurl'] ); //删除缩略图 
			$this->model_name->updateCache();
			$this->success ( "删除成功!" );
		} else {
			$this->error ( "删除失败!" );
		}
	}
	
	/**
	 * +---------------------------------------------------------------
	 * 便携式更改菜单栏目状态
	 * +---------------------------------------------------------------
	 */
	public function updateStatus() {
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		$value = isset ( $_POST ['value'] ) ? intval ( $_POST ['value'] ) : "";
		$field = isset ( $_POST ['field'] ) ? ($_POST ['field']) : "";
		$result = $this->model_name->where ( "id='{$id}'" )->setField ( $field, $value );
		if ($result) {
			$this->model_name->updateCache();
			$this->success ( "处理成功!" );
		} else {
			$this->error ( "处理失败!" );
		}
	}
}