<?php
namespace Jzadmin\Controller;
/**
 * 后台的模型控制器页面
 * @package      	PINGPHP
 * @author          Alan QQ:1073763462
 * @copyright     	Copyright (c) 2016  
 * @version        	PINGPHP企业网站管理系统 v1.0 2016-04-01 $
 *
 */
class ModuleController extends CommonController {
	
	/**
	 * 模型的主页
	 * 
	 */
	public function index() {
		$module_list = $this->model_name->order ( "id desc" )->select ();
		$this->assign ( "module_list", $module_list );
		$this->display ();
	}
	
	/**
	 * 添加模型
	 */
	public function add() {
		if (IS_POST) {
			$data = $this->model_name->create ( $_POST );
			if ($data) {
				$name = $_POST ['name'];
				$tablename = C ( 'DB_PREFIX' ) . $name;
				$db = \Think\DB::getInstance ();
				$tables = $db->getTables ();
				if (in_array ( $tablename, $tables )) {
					$this->error ( "表名已经存在" );
				}
				$_POST ['ctime'] = time ();
				$moduleid = $this->model_name->add ( $_POST );
				if (! $moduleid) {
					$this->error ( $this->model_name->getDbError () );
				}
				//创建表和保存字段
				$field_model = D ( "Field" );
				$field = $field_model->defaultField ();
				$create_table_sql = "CREATE TABLE `" . $tablename . "` ( `id` int(11) unsigned NOT NULL AUTO_INCREMENT,";
				$field_data = array ();
				$field_data ['moduleid'] = $moduleid; //保存到字段表
				foreach ( $field as $key => $value ) {
					$create_table_sql .= " `{$key}` " . $value ['default_value'] . ",";
					$field_data ['field'] = $key;
					$field_data ['name'] = $value ['name'];
					$field_data ['type'] = $value ['type'];
					$field_data ['type_txt'] = $field_model->FieldModel ( $value ['type'] );
					$field_data ['setup'] = isset ( $value ['setup'] ) ? $value ['setup'] : "";
					$field_data ['required'] = isset ( $value ['required'] ) ? $value ['required'] : 0;
					$field_data ['issystem'] = 1;
					$field_model->add ( $field_data );
				}
				$create_table_sql .= "`lang` char(10)  NOT NULL DEFAULT '" . C ( 'DEFAULT_LANG' ) . "', PRIMARY KEY (`id`),
				  KEY `status` (`id`,`status`,`listorder`),
				  KEY `catid` (`id`,`catid`,`status`),
				  KEY `listorder` (`id`,`catid`,`status`,`listorder`) ) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8";
				$db->execute ( $create_table_sql ); //保存创建表
				//添加后台菜单
				$admin_node_model = D ( "AdminNode" );
				$menu_data = array ('name' => 'Content', 'title' => $data ['title'] . "管理", 'remark' => $data ['title'], 'sort' => 10, 'pid' => '80', 'level' => '2', 'module' => 'index/moduleid/' . $moduleid, 'module_id' => $moduleid );
				$menu_pid = $admin_node_model->add ( $menu_data );
				if ($menu_pid) {
					$menu_action_data = array ('index' => $data ['title'] . '列表', 'add' => '添加' . $data ['title'], "edit" => "修改" . $data ['title'], "delAll" => '删除' . $data ['title'],'updateStatus'=>'更改'. $data ['title']."状态" );
					foreach ( $menu_action_data as $key => $value ) {
						$menu_action_value = array ('name' => $key, 'title' => $value, 'pid' => $menu_pid, 'level' => '3' );
						$admin_node_model->add ( $menu_action_value );
					}
				}
				F ( 'MODULE_LIST', null );
				$this->model_name->createRoutes(); //更新路由文件
				$this->success ( "添加成功", U ( 'index' ) );
			} else {
				$this->error ( $this->model_name->getError () );
			}
		} else {
			$this->assign ( "method", "add" );
			$this->display ();
		}
	}
	
	/**
	 * 修改模型
	 */
	public function edit() {
		if (IS_POST) {
			$data = $this->model_name->create ( $_POST );
			if ($data) {
				$_POST ['ctime'] = time ();
				$result = $this->model_name->save ( $_POST );
				if ($result) {
					F ( 'MODULE_LIST', null );
					$admin_node_model = D ( "AdminNode" ); //更改菜单名称
					$menu_action_data = array ('index' => $data ['title'] . '列表', 'add' => '添加' . $data ['title'], "edit" => "修改" . $data ['title'], "delAll" => '删除' . $data ['title'],'updateStatus'=>'更改'. $data ['title']."状态" );
					$node_list=$admin_node_model->where("module_id='".$data['id']."'")->find();
					if ($node_list){
						$admin_node_model->where("id='".$node_list['id']."'")->save(array('title'=>$data ['title']."管理"));
						$node_list=$admin_node_model->where("pid='".$node_list['id']."'")->select();
						foreach ($node_list as $value){
							$admin_node_model->where("id='".$value['id']."'")->save(array('title'=>$menu_action_data[$value['name']]));
						}
					}
					$this->model_name->createRoutes(); //更新路由文件
					$this->success ( "修改成功", U ( 'index' ) );
				} else {
					$this->error ( $this->model_name->getError () );
				}
			} else {
				$this->error ( $this->model_name->getError () );
			}
		} else {
			$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
			if (empty ( $id )) {
				$this->error ( "非法请求" );
			}
			$info = $this->model_name->find ( $id );
			$this->assign ( "info", $info );
			$this->assign ( "method", "edit" );
			$this->display ( "add" );
		}
	}
	
	/**
	 * 查看表字段信息
	 * Enter description here ...
	 */
	public function field() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "非法请求" );
		}
		$field_model = D ( "Field" );
		$field_list = $field_model->where ( "moduleid='" . $id . "'" )->order ( "listorder asc,id asc" )->select ();
		$this->assign ( "field_list", $field_list );
		$this->assign ( "moduleid", $id );
		$this->display ();
	}
	
	/**
	 * 
	 * 删除模型
	 */
	public function del() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "非法请求" );
		}
		$info = $this->model_name->find ( $id );
		if (empty ( $info ))
			$this->error ( "非法请求" );
		$tablename = C ( 'DB_PREFIX' ) . $info ['name'];
		$m = $this->model_name->delete ( $id );
		if ($m) {
			
			$this->model_name->execute ( "DROP TABLE IF EXISTS `" . $tablename . "`" ); //删除表
			//删除字段
			$field_model = D ( "Field" );
			$field_model->where ( "moduleid='" . $id . "'" )->delete ();
			
			//删除菜单
			$admin_node_model = D ( "AdminNode" );
			$menu_list = $admin_node_model->where ( "module_id='" . $id . "'" )->field ( "id" )->select ();
			foreach ( $menu_list as $value ) {
				$admin_node_model->where ( "pid='" . $value ['id'] . "'" )->delete ();
			}
			$admin_node_model->where ( "module_id='" . $id . "'" )->delete ();
			F ( 'MODULE_LIST', null );
			$this->model_name->createRoutes(); //更新路由文件
			$this->success ( '删除成功' );
		} else {
			$this->error ( "删除失败" );
		}
	}
	/**
	 * +---------------------------------------------
	 * 添加字段
	 * +--------------------------------------------
	 */
	public function field_add() {
		if ($_GET ['isajax']) {
			$this->assign ( $_GET );
			$this->assign ( $_POST );
			$this->display ( 'field_type' );
			exit ();
		}
		$field_model = D ( "Field" );
		$field_list = $field_model->FieldModel ();
		if (IS_POST) {
			$result_field=$field_model->where("moduleid='".intval($_POST['moduleid'])."' and field='".$_POST['field']."'")->count();
			if($result_field>0){
				$this->error ( '字段已经存在');
			}
			$addfieldsql = $field_model->get_tablesql ( $_POST, 'add' ); 
			if ($_POST ['setup'])
				$_POST ['setup'] = array2serialize ( $_POST ['setup'] );
			$_POST ['status'] = 0;
			$_POST ['type_txt'] = $field_model->FieldModel ( $_POST ['type'] );
			if (false === $field_model->create ()) {
				$this->error ( $field_model->getError () );
			}
			if ($field_model->add () !== false) {
				if (is_array ( $addfieldsql )) {
					foreach ( $addfieldsql as $sql ) {
						$field_model->execute ( $sql );
					}
				} else {
					if ($addfieldsql)
						$field_model->execute ( $addfieldsql );
				}
				$field_model->updateCacheField($_POST ['moduleid']);
				$this->success ( "添加成功", U ( 'field', array ('id' => $_POST ['moduleid'] ) ) );
			} else {
				$this->error ( '添加失败: ' . $field_model->getDbError () );
			}
		} else {
			$moduleid = isset ( $_GET ['moduleid'] ) ? intval ( $_GET ['moduleid'] ) : "";
			if (empty ( $moduleid )) {
				$this->error ( "非法请求" );
			}
			$this->assign ( "info", array ("moduleid" => $moduleid ) );
			$this->assign ( "method", "field_add" );
			$this->assign ( "field_list", $field_list );
			$this->display ();
		}
	}
	
	/**
	 * +---------------------------------------------
	 * 修改字段
	 * +--------------------------------------------
	 */
	public function field_edit() {
		$field_model = D ( "Field" );
		$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "非法请求" );
		}
		$info = $field_model->find ( $id );
		$field_list = $field_model->FieldModel ();
		if (IS_POST) {
			$editfieldsql = $field_model->get_tablesql ( $_POST, 'edit' );
			if ($_POST ['setup'])
				$_POST ['setup'] = array2serialize ( $_POST ['setup'] );
			$create_data = $field_model->create ();
			if (false === $create_data) {
				$this->error ( $field_model->getError () );
			}
			if (false !== $field_model->save ( $create_data )) { 
				if (is_array ( $editfieldsql )) {
					foreach ( $editfieldsql as $sql ) {
						$field_model->execute ( $sql );
					}
				} else if (!empty($editfieldsql)){
					$r = $field_model->execute ( $editfieldsql );
				}
				$field_model->updateCacheField($_POST ['moduleid']);
				$this->success ( '修改成功', U ( 'field', array ('id' => $_POST ['moduleid'] ))  );
			} else { 
				$this->success ( '修改失败: ' . $field_model->getDbError () );
			}
		} else {
			
			if ($info ['setup']) {
				$info ['setup'] = serialize2array ( $info ['setup'] );
				$info ['setup'] ['type'] = $info ['type'];
			}
			$this->assign ( "info", $info );
			$this->assign ( "method", "field_edit" );
			$this->assign ( "field_list", $field_list );
			$this->display ( "field_add" );
		}
	}
	/**
	 * +---------------------------------------------
	 * 删除字段
	 * +--------------------------------------------
	 */
	public function delete() {
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "非法请求" );
		}
		$field_model = D ( "Field" );
		$r = $field_model->find ( $id );
		if (empty ( $r ))
			$this->error ( "非法请求" );
		$result=$field_model->delete ( $id );
		if ($result){
			$moduleid = $r ['moduleid'];
			$field = $r ['field'];
			$module_model = D ( "Module" );
			$module_info = $module_model->getModuleIdByModuleId ( $moduleid );
			$tablename = C ( 'DB_PREFIX' ) . strtolower ( $module_info ['name'] );
			$field_model->execute ( "ALTER TABLE `$tablename` DROP `$field`" );
			$field_model->updateCacheField($moduleid);
		}
		$this->success ( '删除成功' );
	}
	
	/**
	 * +---------------------------------------------
	 * 更新字段排序
	 * +--------------------------------------------
	 */
	public function listorder(){
		$listorder=isset($_POST['listorder']) ? $_POST['listorder']: 0;
		$field_model = D ( "Field" );
		foreach ($listorder as $key=>$value){
			$field_model->where("id='".$key."'")->save(array('listorder'=>$value));
		}
		$field_model->updateCacheField($_POST ['moduleid']);
		$this->success("更新成功",U('field',array('id'=>$_POST['moduleid'])));
	}

}