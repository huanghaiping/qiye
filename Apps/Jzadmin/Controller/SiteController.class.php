<?php
namespace Jzadmin\Controller;
class SiteController extends CommonController {
	
	protected $groupId = array (1 => '站点配置', 2 => '用户中心', 3 => '系统参数' );
	protected $user_config = array ('NAME', 'APPkEY', 'APPSECRET', 'CALLBACK' );
	/*
	 * 设置站点信息
	 */
	public function index() {
		//加载用户配置文件信息
		if (IS_POST) {
			$data = $user_config = array ();
			$where = "";
			if ($this->lang&&$_POST['groupid']!=3)
				$where = " and lang='" . $this->lang . "'";
			$multpart_array = array ();
			//判断是否有上传文件
			if (! empty ( $_FILES )) {
				$fiel_keys = array_keys ( $_FILES );
				$upload_img_info = $this->model_name->uploadImg ( $fiel_keys, 'images' );
				$_POST = array_merge ( $_POST, $upload_img_info );
				foreach ( $fiel_keys as $value ) {
					unset ( $_POST [$value . "_txt"] );
				}
			}
			unset($_POST['groupid']);
			foreach ( $_POST as $key => $value ) {
				if (array_key_exists ( $key, $multpart_array )) {
					continue; //当存入已经需要删除的数据就跳过
				}
				if ($value == 'multipart') { //当是多参数时候组装数据
					$data = array ();
					foreach ( $this->user_config as $v ) {
						$data [$v] = isset ( $_POST [$key . "_" . $v] ) ? $_POST [$key . "_" . $v] : "";
						$multpart_array [$key . "_" . $v] = $v; //存入需要删除的数组
						unset ( $_POST [$key . "_" . $v] );
					}
					$value = serialize ( $data );
					$user_config [$key] = $data;
				} else {
					$user_config [$key] = $value;
					$value = is_array ( $value ) ? implode ( ",", $value ) : $value;
				}
				$this->model_name->where ( "varname='" . $key . "'" . $where )->save ( array ('value' => $value ) );
			}	
			$this->model_name->updateConfig (); //更改配置文件
			$this->success ( "设置成功", U ( 'index' ) );
		
		} else {
			$result = $this->model_name->where ( "lang='" . $this->lang . "'" )->select ();
			if ($result) {
				$row = array ();
				foreach ( $result as $value ) {
					$value ['input_string'] = $this->model_name->createInput ( $value );
					$row [$value ['groupid']] [] = $value;
				}
			}
			unset ( $result );
			$this->assign ( "groupid", $this->groupId );
			$this->assign ( "system_list", $row );
			$this->display ( "Index:system" );
		}
	}
	
	/**
	 * 
	 * 添加系统参数
	 */
	public function add() {
		if ($_POST ['input_type'] == "multipart") { //当为多参数的时候，就固定格式参数
			$data = array ();
			foreach ( $this->user_config as $v ) {
				if ($v == 'NAME')
					$data [$v] = isset ( $_POST ['info'] ) ? $_POST [$_POST ['info']] : "";
				else
					$data [$v] = "";
			}
			$_POST ['value'] = serialize ( $data );
		}
		$create_data = $this->model_name->create ( $this->model_name->createData ( $_POST ) );
		if ($create_data) {
			$lang_model = D ( "Lang" );
			$lang_list = $lang_model->getLang ();
			foreach ( $lang_list as $value ) {
				$create_data ['lang'] = $value ['mark'];
				$count=$this->model_name->where("varname='".$create_data['varname']."' and lang='".$create_data ['lang']."'")->count();
				if ($count<=0){
					$this->model_name->add ( $create_data );
				}
			}
			$this->model_name->updateConfig (); //更改配置文件
			$this->success ( "添加成功" );
		} else {
			$this->error ( $this->model_name->getError () );
		}
	
	}
	
	/**
	 * 清除缓存
	 * Enter description here ...
	 */
	public function cleancache() {
		$this->model_name->clearCache();
		$this->success ( "缓存文件已清除",U('Index/main') );
	
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 模板选择
	 * +---------------------------------------------------------------
	 */
	public function templte() {
		$theme = isset ( $_GET ['t'] ) ? addSlashesFun ( $_GET ['t'] ) : "";
		if (empty ( $theme )) {
			$default_theme = $this->model_name->getConfirByKey ( "DEFAULT_THEME" );
			$templte_path = APP_PATH . "Home/" . C ( 'DEFAULT_V_LAYER' ) . "/";
			$filed = glob ( $templte_path . '*' );
			foreach ( $filed as $key => $v ) {
				$arr [$key] ['name'] = basename ( $v );
				if (is_file ( $templte_path . $arr [$key] ['name'] . '/default.jpg' )) {
					$arr [$key] ['preview'] = $templte_path . $arr [$key] ['name'] . '/default.jpg';
				} else {
					$arr [$key] ['preview'] = __ROOT__ . '/Public/nopic.jpg';
				}
				$arr [$key] ['preview'] = str_replace ( "./", "/", $arr [$key] ['preview'] );
				if ($default_theme == $arr [$key] ['name'])
					$arr [$key] ['use'] = 1;
			}
			$this->assign ( 'themes', $arr );
			$this->display ( "Index:templte" );
		} else {
			if ($theme) {
				$r = $this->model_name->where ( "varname='DEFAULT_THEME' and lang='" . $this->lang . "' " )->setField ( 'value', $theme );
				$this->model_name->updateConfig (); //更改配置文件
				$this->model_name->clearCache();
				$this->success ( '设置成功' );
			} else {
				$this->error ( '设置失败' );
			}
		}
	}
	
	/**
	 * 图片的水印配置参数
	 * Enter description here ...
	 */
	public function watermark(){
		if (IS_POST){
			//判断是否有上传文件字体,Public/images/font/
			if (isset( $_FILES['watemard_text_face'] )&&! empty ( $_FILES['watemard_text_face'] )) {
				$upload_img_info = $this->model_name->uploadImg ( array('watemard_text_face'), 'images/' ,array('maxSize'=>20971520,'rootPath'=>'./Public/','savePath'=>'images/font/','exts'=>array('ttf','ttc')));
				$_POST['watemard_text_face']=$upload_img_info['watemard_text_face'];
			}
			//判断是否上传水印图片
			if (isset( $_FILES['watermark_img'] )&&! empty ( $_FILES['watermark_img'] )) {
				$upload_img_info = $this->model_name->uploadImg ( array('watermark_img'), 'images/' ,array('rootPath'=>'./Public/'));
				$_POST['watermark_img']=$upload_img_info['watermark_img'];
			}
			F("watermark",$_POST,INCLUDE_PATH);
			$this->success("添加成功");
		}else{
			$info=F("watermark",'',INCLUDE_PATH);
			$this->assign("info",$info);
			$this->display("Index:watermark");
		}
	}

}