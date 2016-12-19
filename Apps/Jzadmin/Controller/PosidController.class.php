<?php
namespace Jzadmin\Controller;
/**
 * 
 * 推荐位管理
 * @author Alan
 *
 */
class PosidController extends CommonController {
	
	/**
	 * 
	 * 推荐位首页
	 */
	public function index() {
		$recomment_list=$this->model_name->order("listorder desc,id desc")->select();
		if ($recomment_list){
			//获取模型类型
			$module_model=D("Module");
			$model_list=$module_model->getModule(); 
			foreach ($recomment_list as $key=>$value){
				$value['catename']=array_key_exists($value['catid'], $model_list) ? $model_list[$value['catid']]['title']:""; 
				$recomment_list[$key]=$value;
			}
			F("RECOMEND_LIST",$recomment_list);
			$this->assign("list",$recomment_list);
		}
		$this->display ();
	}
	
	/**
	 * 
	 * 添加推荐位
	 */
	public function add() {
		if (IS_POST) {
			$data = array ();
			$data ['name'] = isset ( $_POST ['name'] ) ? addslashes ( $_POST ['name'] ) : "";
			if (empty ( $data ['name'] )) {
				$this->error ( "推荐位名称不能为空" );
			}
			$data ['listorder'] = isset ( $_POST ['listorder'] ) ? intval ( $_POST ['listorder'] ) : "";
			$data ['catId'] = isset ( $_POST ['catId'] ) ? intval ( $_POST ['catId'] ) : "";
			$data ['ctime'] = time ();
			$result = $this->model_name->add ( $data );
			if ($result) {
				F("RECOMEND_LIST",NULL);
				$this->success ( "添加成功", U ( 'index' ) );
			} else {
				$this->error ( "添加失败" );
			}
		} else {
			//获取模型类型
			$module_model=D("Module");
			$this->assign("module_list",$module_model->getModule());
			$this->assign ( "method", "add" );
			$this->display ();
		}
	}
	
	/**
	 * 
	 * 修改推荐位
	 */
	public function edit() {
		if (IS_POST) {
			$data = array ();
			$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
			$data ['name'] = isset ( $_POST ['name'] ) ? addslashes ( $_POST ['name'] ) : "";
			if (empty ( $data ['name'] )) {
				$this->error ( "推荐位名称不能为空" );
			}
			$data ['listorder'] = isset ( $_POST ['listorder'] ) ? intval ( $_POST ['listorder'] ) : "";
			$data ['catId'] = isset ( $_POST ['catId'] ) ? intval ( $_POST ['catId'] ) : "";
			$data ['ctime'] = time ();
			$result = $this->model_name->where ( "id='" . $id . "'" )->save ( $data );
			if ($result) {
				F("RECOMEND_LIST",NULL);
				$this->success ( "修改成功", U ( 'index' ) );
			} else {
				$this->error ( "修改失败" );
			}
		} else {
			$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
			$info=$this->model_name->find($id);
			$this->assign("info",$info);
			$this->assign ( "method", ACTION_NAME );
			//获取模型类型
			$module_model=D("Module");
			$this->assign("module_list",$module_model->getModule());
			$this->display ("add");
		}
	}
	/**
	 * 
	 * 删除推荐位
	 */
	public function del() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		$result = $this->model_name->where ( "id='" . $id . "'" )->delete (  );
		if ($result) {
			F("RECOMEND_LIST",NULL);
			$this->success ( "删除成功", U ( 'index' ) );
		} else {
			$this->error ( "删除失败" );
		}
	}
}