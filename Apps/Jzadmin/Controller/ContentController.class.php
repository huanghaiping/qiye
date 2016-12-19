<?php
namespace Jzadmin\Controller;
/**
 * 
 * 内容模型管理页面
 * @author Alan
 *
 */
class ContentController extends CommonController {
	
	protected $moduleId = ""; //模型的ID
	protected $menu_model=array();
	

	protected function _initialize() {
		parent::_initialize ();
		$this->moduleId=isset($_REQUEST['moduleid']) ? intval($_REQUEST['moduleid']) : "";
		if (empty($this->moduleId)){
			$this->error("非法请求");
		}
		$this->menu_model=D ( "Menu" );
		//获取模型
		$module_info=$this->menu_model->getMenuByTypeid($this->moduleId);
		$this->assign("module_info",$module_info);
		$this->model_name=$module_info['controller_name'];
	}
	
 
	
	/**
	 * 文章控制的首页
	 */
	public function index() {
		$param = $condition = $request = array ();
		$request ['catid'] = isset ( $_REQUEST ['catid'] ) ? intval ( $_REQUEST ['catid'] ) : "";
		$request ['status'] = isset ( $_REQUEST ['status'] ) ? intval ( $_REQUEST ['status'] ) : "";
		$keyword = isset ( $_REQUEST ['keyword'] ) ? $_REQUEST ['keyword'] : "";
		$posid = isset ( $_REQUEST ['posid'] ) ? intval ( $_REQUEST ['posid'] ) : "";
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
		//获取模型
		$category_list = $this->menu_model->getMenuByModuleId ($this->moduleId,$request ['catid']);		
		$sql = "select * from " . C ( "DB_PREFIX" ) . strtolower ( $this->model_name ) . $condition_sql . " order by id desc ";
		$result = $this->menu_model->getPageData ( $sql, $param, 20 );
		if ($result ['data']) {
			foreach ( $result ['data'] as $key => $value ) {
				$result ['data'] [$key] ['typename'] = array_key_exists ( $value ['catid'], $category_list ) ? $category_list [$value ['catid']] ['title'] : "";
			}
		}
		
		$this->assign ( "category_list", $category_list);
		$this->assign("moduleId",$this->moduleId);
		//获取推荐位
		$flag_list = $this->menu_model->getMenuFlag ($this->moduleId);
		$this->assign ( "flag_list", $flag_list );
		$this->assign ( "userlist", $result ['data'] );
		$this->assign ( "page", $result ['page'] );
		$this->display ();
	}
	
	/**
	 * 
	 * 添加资讯
	 */
	public function add() {
		$field_model=D("Field");
		$field_list=$field_model->getModuleField($this->moduleId); 
		if (IS_POST) {
			$model_name=D($this->model_name);
			$moduleid=$_POST['moduleid'];
			$_POST=$field_model->checkfield($field_list,$_POST);
			$_POST['lang']=$this->lang;
			$rules=$field_model->validate($field_list);
			$createData = $model_name->validate($rules)->create ( $_POST );
			if ($createData) {
				$result = $model_name->add ( $createData );
				if ($result) {
					$this->success ( "添加成功", U ( 'index',array('moduleid'=>$moduleid) ) );
				} else {
					$this->error ( $model_name->getError () );
				}
			} else {
				$this->error ( $model_name->getError () );
			}
		} else {
			if ($field_list){
				$field_type=getSubByKey($field_list,"type");
			}
			$this->assign("field_list",$field_list);
			$form = new \Org\Util\Form ( );
			$this->assign("form",$form);
			$this->assign("field_type",$field_type);
			$this->assign ( "method", "add" );
			$module_model=D("Module");
			$module_info=$module_model->getModuleIdByModuleId($this->moduleId);
			$template=$module_info&&!empty($module_info['template']) ? $module_info['template'] : "";
			$this->display ($template);
		}
	}
	
	/**
	 * 
	 * 修改资讯
	 */
	public function edit() {
		$model_name=D($this->model_name);
		$idName = $model_name->getPk ();
		$field_model=D("Field");
		$field_list=$field_model->getModuleField($this->moduleId);
		if (IS_POST) {
			$moduleid=$_POST['moduleid'];
			$_POST=$field_model->checkfield($field_list,$_POST);
			$_POST['lang']=$this->lang;
			$rules=$field_model->validate($field_list);
			$createData = $model_name->validate($rules)->create ( $_POST );
			if ($createData) {
				$result = $model_name->save ( $createData );
				if ($result!== false) {
					$this->success ( "修改成功", U ( 'index' ,array('moduleid'=>$moduleid)) );
				} else {
					$this->error ( $model_name->getError () );
				}
			} else {
				$this->error ( $model_name->getError () );
			}
		} else {
			$id = isset ( $_GET [$idName] ) ? intval ( $_GET [$idName] ) : "";
			$info =$model_name->find ( $id );
			if (! $info) {
				$this->error ( "内容不存在" );
			}
			if ($field_list){
				$field_type=getSubByKey($field_list,"type");
			}
			$this->assign("field_list",$field_list);
			$form = new \Org\Util\Form ($info );
			$this->assign("form",$form);
			$this->assign("field_type",$field_type);
			$this->assign("info",$info);
			$this->assign ( "method", "edit" );
			$module_model=D("Module");
			$module_info=$module_model->getModuleIdByModuleId($this->moduleId);
			$template=$module_info&&!empty($module_info['template']) ? $module_info['template'] : "add";
			$this->display ( $template);
		}
	}
	
	/**
	 * 
	 * 删除内容
	 */
	public function delAll() {
		$files = isset ( $_REQUEST ['files'] ) ? $_REQUEST ['files'] : "";
		if (empty ( $files )) {
			$this->error ( "请选择要删除的内容？" );
		}
		$idStr = is_array ( $files ) ? implode ( ",", $files ) : $files;
		$module_name=D($this->model_name);
		$field_model=D("Field");
		$field_list=$field_model->getModuleField($this->moduleId);
		$del_type=array('image','images','file','files','editor');
		$del_field=array();
		foreach ($field_list as $value){
			if (in_array($value['type'], $del_type)){
				$del_field[$value['field']]=$value['type'];
			}
		}
		$info = $module_name->where ( "id in({$idStr})" )->field ( "id,".implode(",", array_keys($del_field)) )->select ();
		foreach ( $info as $value ) {
			$result = $module_name->where ( "id='" . $value ['id'] . "'" )->delete ();
			if ($result) {
				foreach ($del_field as $k=>$v){
					if (!empty( $value [$k])){
						if ($v=='editor'){
							$content = stripslashes ( $value [$k] );
							delContentImg ( $content ); // 删除文章内容里的图片和附件
						}elseif ($v=="images"||$v=='files'){
							$options = explode(":::",$value [$k]);
							if(is_array($options)){
								foreach($options as  $keys=>$rs) {
									$v = explode("|",$rs);
									@unlink ( "." . $v[0] ); //删除缩略图 
								}
							}
						}else{
							@unlink ( "." . $value [$k] ); //删除缩略图 
						}
					}
				}
				
				
			}
		}
		$this->success ( "删除成功!" );
	}
	
	/**
	 * 
	 * 更改文章的状态
	 */
	public function updateStatus() {
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		$value = isset ( $_POST ['value'] ) ? intval ( $_POST ['value'] ) : "";
		$field = isset ( $_POST ['field'] ) ? ($_POST ['field']) : "";
		$module_name=D($this->model_name);
		$result = $module_name->where ( "id='{$id}'" )->setField ( $field, $value );
		if ($result) {
			$this->success ( "处理成功!" );
		} else {
			$this->error ( "处理失败!" );
		}
	}
}