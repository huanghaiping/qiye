<?php
namespace Jzadmin\Controller;
class AreaController extends CommonController {
	//过滤查询字段
	function _filter(&$map) {
		$map ['area_name'] = array ('like', "%" . $_POST ['name'] . "%" );
	}
	/**
	 * 查看联动菜单列表
	 * 
	 */
	public function index() {
		
		$map=array();
		$map ["parent_id"] = isset ( $_GET ['id'] ) ?  intval($_GET ['id']) : 0;
		$menu = $this->model_name->where ( $map )->order ( 'listorder' )->select ();
		$this->assign ( "list", $menu );
		$this->display ();
	}
		
	public function _before_add() {
		if (isset ( $_GET ['id'] )) {
			$this->parent_id = $_GET ['id'];
		}
	}
	/**
	 * 修改联动菜单
	 * 
	 */
	public function edit() {
		if (IS_POST) {
			if (false === $this->model_name->create ()) {
				$this->error ( $this->model_name->getError () );
			}
			$map ['id'] = $_POST ['parent_id'];
			$level = $this->model_name->where ( $map )->getField ( 'area_type' );
			$this->model_name->area_type= ! isset ( $level ) ?  0 : $level + 1;	
			$list = $this->model_name->save (); // 更新数据
			if (false !== $list) {
				$this->success ( '编辑成功!' );
			} else {	
				$this->error ( '编辑失败!' ); //错误提示
			}
		} else {
			$id = $_REQUEST [$this->model_name->getPk ()];
			$vo = $this->model_name->getById ( $id );
			$this->assign ( 'info', $vo );
			$this->display ();
		
		}
	}
	//彻底删除记录
	public function foreverdelete() {
		//删除指定记录
		if (! empty ( $this->model_name )) {
			$pk = $this->model_name->getPk ();
			$id = $_REQUEST [$pk];
			
			if (isset ( $id )) {
				$menu = $this->model_name->order ( 'listorder' )->select ();
				$idlist = $id . ',' . $this->getAreaId ( $menu, $id );
				$idlist = substr ( $idlist, 0, strlen ( $idlist ) - 1 );
				$condition = array ($pk => array ('in', explode ( ',', $idlist ) ) );
				if (false !== $this->model_name->where ( $condition )->delete ()) {
					$this->success ( '删除成功！' );
				} else {
					$this->error ( '删除失败！' );
				}
			} else {
				$this->error ( '非法操作' );
			}
		}
	
		//$this->forward();
	}
	
	/**
	 * 添加联动菜单
	 * Enter description here ...
	 */
	public function add() {
		if (IS_POST) {
			if (false === $this->model_name->create ()) {
				$this->error ( $this->model_name->getError () );
			}
			$map ['id'] = $_POST ['parent_id'];
			$level = $this->model_name->where ( $map )->getField ( 'area_type' );
			$this->model_name->area_type= ! isset ( $level ) ? 0 : $level + 1;
			//保存当前数据对象
			$list = $this->model_name->add ();
			if ($list !== false) { 
				$this->success ( '新增成功!', U ( 'index' ) );//保存成功
			} else {
				$this->error ( '新增失败!' );//失败提示
			}
		
		} else {
			$this->display ();
		}
	}
	
	//将数组转化为树形数组   
	function getAreaId($data, $pid) {
		foreach ( $data as $k => $v ) {
			if ($v ['parent_id'] == $pid) {
				$id .= $this->getAreaId ( $data, $v ['id'] );
				$id .= $v ['id'] . ',';
			}
		}
		return $id;
	}
	
	/**
	 * ajax 获取地区
	 * Enter description here ...
	 */
	public function getArea() {
		$area = $this->model_name->getAreaList ( $_REQUEST ['areaId'] );
		$this->ajaxReturn ( $area );
	}

}

?>
