<?php
namespace Jzadmin\Controller;
class LangController extends CommonController {
	
	/**
	 * +--------------------------------------------------------
	 * 多语言列表
	 * +--------------------------------------------------------
	 */
	public function index() {
		$list = $this->model_name->order ( "listorder desc,id desc" )->select ();
		$this->assign ( "list", $list ); //获取语言结构
		$this->display ();
	}
	
	/**
	 * +--------------------------------------------------------
	 * 添加语言
	 * +--------------------------------------------------------
	 */
	public function add() {
		if (IS_POST) {
			$data = array ();
			$data ['name'] = isset ( $_POST ['name'] ) ? addSlashesFun ( $_POST ['name'] ) : "";
			if (empty ( $data ['name'] )) {
				$this->error ( "语言名称不能为空!" );
			}
			$data ['mark'] = isset ( $_POST ['mark'] ) ? ($_POST ['mark']) : "";
			$data ['listorder'] = isset ( $_POST ['listorder'] ) ? intval ( $_POST ['listorder'] ) : "50";
			$data ['status'] = isset ( $_POST ['status'] ) ? intval ( $_POST ['status'] ) : "0";
			$data ['domain'] = isset ( $_POST ['domain'] ) ?  ( $_POST ['domain'] ) : "0";
			unset ( $data [cid] );
			
			//上传国旗图片
			$img_post = $this->model_name->uploadImg ( array ('flag' ), "images" );
			$data = array_merge ( $data, $img_post );
			if ($this->model_name->where ( "name='" . $data ['name'] . "' or mark='" . $data ['mark'] . "'" )->count () == 0) {
				$create_data = $this->model_name->create ( $data );
				if ($create_data) {
					$lang_id = $this->model_name->add ( $create_data );
					if ($lang_id) {
						//复制语言文件
						if (! empty ( $_POST ['copy_name'] )) {
							$lang_param_model = D ( "LangParam" );
							$copy_lang_list = $lang_param_model->where ( "lang_id='" . $_POST ['copy_name'] . "'" )->select ();
							if ($copy_lang_list) {
								foreach ( $copy_lang_list as $value ) {
									$param = array ('lang_id' => $lang_id, "mark" => $data ['mark'], 'field' => $value ['field'], 'value' => $value ['value'], 'ctime' => time (), 'alisa' => $value ['alisa'] );
									$lang_param_model->add ( $param );
								}
							}
						}
						$this->model_name->updateLangCache ( $lang_id, $data ['mark'] );
						F ( 'Lang', NULL, INCLUDE_PATH ); //删除缓存
						$this->model_name->updateCache ();
						$this->success ( '添加成功', U ( 'index' ) );
					} else {
						$this->error ( '添加失败', U ( 'index' ) );
					}
				} else {
					$this->error ( $this->model_name->getError () );
				}
			} else {
				$this->error ( '语言已经存在', U ( 'index' ) );
			}
		} else {
			$lang_list = $this->model_name->getLang ();
			$this->assign ( "lang_list", $lang_list );
			$this->display ();
		}
	}
	
	/**
	 * +--------------------------------------------------------
	 * 修改语言
	 * +--------------------------------------------------------
	 */
	public function edit() {
		if (IS_POST) {
			$data ['name'] = isset ( $_POST ['name'] ) ? addSlashesFun ( $_POST ['name'] ) : "";
			$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
			if (empty ( $data ['name'] )) {
				$this->error ( "语言名称不能为空!" );
			}
			if (empty ( $id )) {
				$this->error ( "数据请求错误" );
			}
			$data ['mark'] = isset ( $_POST ['mark'] ) ? ($_POST ['mark']) : "";
			$data ['listorder'] = isset ( $_POST ['listorder'] ) ? intval ( $_POST ['listorder'] ) : "50";
			$data ['status'] = isset ( $_POST ['status'] ) ? intval ( $_POST ['status'] ) : "0";
			$data ['domain'] = isset ( $_POST ['domain'] ) ?  ( $_POST ['domain'] ) : "0";
			
			//上传国旗图片
			$img_post = $this->model_name->uploadImg ( array ('flag' ), "images" );
			$data = array_merge ( $data, $img_post );
			$old_info = $this->model_name->where ( "id='{$id}'" )->find ();
			if ($this->model_name->where ( "id='{$id}'" )->save ( $data )) {
				if ($old_info ['mark'] != $data ['mark']) { //更换国家图标
					$this->model_name->changMark ( $old_info ['id'], $old_info ['mark'], $data ['mark'] );
				}
				F ( 'Lang', NULL, INCLUDE_PATH ); //删除缓存
				$this->model_name->updateCache ();
				$this->success ( '修改成功', U ( 'index' ) );
			} else {
				$this->error ( '修改失败', U ( 'index' ) );
			}
		
		} else {
			$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
			if (empty ( $id )) {
				$this->error ( "数据请求错误" );
			}
			$info = $this->model_name->where ( "id='{$id}'" )->limit ( 1 )->find ();
			$this->assign ( "info", $info );
			$this->display ();
		}
	}
	/**
	 * +--------------------------------------------------------
	 * 删除语言
	 * +--------------------------------------------------------
	 */
	public function delCategory() {
		$id = isset ( $_GET ['id'] ) ? intval ( $_GET ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误" );
		}
		$info = $this->model_name->where ( "id='{$id}'" )->find ();
		$result = $this->model_name->where ( "id='{$id}'" )->delete ();
		if ($result) {
			@unlink ( "." . $info ['flag'] ); //删除国旗图标
			$lang_param_model = D ( "LangParam" );
			$lang_param_model->where ( "lang_id='" . $info ['id'] . "'" )->delete ();
			$file_new_name = APP_PATH . C ( "DEFAULT_MODULE" ) . "/Lang/" . $info ['mark'] . ".php"; //删除语言包
			@unlink ( $file_new_name );
			F ( 'Lang', NULL, INCLUDE_PATH ); //删除缓存
			$this->model_name->updateCache ();
			$this->success ( "删除成功!", U ( 'index' ) );
		} else {
			$this->error ( "删除失败!", U ( 'index' ) );
		}
	}
	
	/**
	 * +--------------------------------------------------------
	 * 便携式修改列表状态
	 * +--------------------------------------------------------
	 */
	public function updateStatus() {
		$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : "";
		$status = isset ( $_POST ['status'] ) ? intval ( $_POST ['status'] ) : "";
		$field = isset ( $_POST ['field'] ) ? ($_POST ['field']) : "";
		if (empty($id)||empty($field)){
			$this->error("非法请求");
		}
		$result =$this->model_name->where ( "id='{$id}'" )->setField ( $field, $status);
		if ($result) {
			F ( 'Lang', NULL, INCLUDE_PATH ); //删除缓存
			$this->model_name->updateCache ();
			$this->success ( "处理成功!" );
		} else {
			$this->error ( "处理失败!" );
		}
	}
	
	/**
	 * +--------------------------------------------------------
	 * 设置语言
	 * +--------------------------------------------------------
	 */
	public function setlang() {
		$lang_id = isset ( $_REQUEST ['id'] ) ? $_REQUEST ['id'] : "";
		if (empty ( $lang_id )) {
			$this->error ( "非法请求" );
		}
		$lang_param_model = D ( "LangParam" );
		$param_list = $lang_param_model->where ( "lang_id='" . $lang_id . "'" )->order ( "id desc " )->select ();
		if (IS_POST) {
			$data = array ();
			$mark = LANG_SET;
			foreach ( $param_list as $value ) {
				$data ['value'] = isset ( $_POST [$value ['field'] . "_field"] ) ? addSlashesFun ( $_POST [$value ['field'] . "_field"] ) : "";
				$data ['alisa'] = isset ( $_POST [$value ['field'] . "_alisa"] ) ? addSlashesFun ( $_POST [$value ['field'] . "_alisa"] ) : "";
				$lang_param_model->where ( "id='" . $value ['id'] . "'" )->save ( $data );
				$mark = $value ['mark'];
			}
			$this->model_name->updateLangCache ( $lang_id, $mark );
			$this->success ( "设置成功", U ( 'index' ) );
		} else {
			$this->assign ( "set_lang_list", $param_list );
			$this->assign ( "lang_id", $lang_id );
			$this->display ();
		}
	}
	
	/**
	 * +--------------------------------------------------------
	 * 添加语言参数
	 * +--------------------------------------------------------
	 */
	public function addparam() {
		$lang_list = $this->model_name->getLang ();
		if (IS_POST) {
			$data = array ();
			$data ['field'] = isset ( $_POST ['name'] ) ? addSlashesFun ( $_POST ['name'] ) : "";
			if (empty ( $data ['field'] )) {
				$this->error ( "参数名称不能为空!" );
			}
			$data ['alisa'] = isset ( $_POST ['alisa'] ) ? addSlashesFun ( $_POST ['alisa'] ) : "";
			$data ['ctime'] = time ();
			if ($lang_list) {
				$lang_param_model = D ( "LangParam" );
				$data ['field'] = strtoupper ( $data ['field'] );
				foreach ( $lang_list as $value ) {
					$data ['lang_id'] = $value ['id'];
					$data ['mark'] = $value ['mark'];
					$data ['value'] = isset ( $_POST [$value ['mark'] . "_" . $value ['id']] ) ? $_POST [$value ['mark'] . "_" . $value ['id']] : "";
					if (!empty($data ['value'])){
						$data ['value']=addSlashesFun($data ['value']);
						$lang_param_model->add ( $data );
						$this->model_name->updateLangCache ( $value ['id'], $value ['mark'] ); //更新缓存
					}
				}
				$this->success ( "添加成功" );
			} else {
				$this->error ( "添加失败" );
			}
		} else {
			$this->assign ( "lang_list", $lang_list );
			$this->display ();
		}
	}
}