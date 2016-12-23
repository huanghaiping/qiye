<?php
namespace Jzadmin\Controller;
class AttachmentController extends CommonController{
	
	protected $moduleId = ""; //模型的ID
	protected $menu_model=array();
	protected $moduel_info=array();
	
	/**
	 * 初始化上传项目
	 * @see Admin\Controller.CommonController::_initialize()
	 */
	protected function _initialize() {
		//parent::_initialize ();
		$this->moduleId=isset($_REQUEST['moduleid']) ? intval($_REQUEST['moduleid']) : "";
		if (empty($this->moduleId)){
			$this->error("非法请求");
		}
		$this->menu_model=D ( "Menu" );
		//获取模型
		$this->moduel_info=$this->menu_model->getMenuByTypeid($this->moduleId);
		$this->assign("module_info",$this->moduel_info);
		$this->model_name=$this->moduel_info['controller_name'];
	}
	
	/**
	 * 加载上传页面
	 * 
	 */
	public function index(){
		$postd=array('moduleid','field','type','minlength','maxlength','path','upload_maxsize','upload_allowext','upload_maxnum','module');
		$postdata=array();
		foreach((array)$_REQUEST as $key=>$res){
			if(in_array($key,$postd))$postdata[$key]=$res;
		}
		$this->assign("info",$postdata);
		$this->display();
	}
	
	/**
	 * +-------------------------------------
	 * 上传文件或者图片
	 * +-------------------------------------
	 */
	public function upload(){
		
		header('Content-type: application/json');
		$verifyToken = md5('unique_salt' . $_POST['timestamp']);
		if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
			$field=isset($_POST['field'])  ? addslashes($_POST['field'] ) : "Filedata";
			$upload_maxnum=isset($_POST['upload_maxnum'])  ? $_POST['upload_maxnum'] : 1;
			$upload_allowext=isset($_POST['upload_allowext'])  ? $_POST['upload_allowext'] : "jpg, gif, png, jpeg";
			$watermark=isset($_POST['watermark'])  ? $_POST['watermark'] : 0; //是否添加水印
			$patch=isset($_POST['path'])  ? $_POST['path'] : "";
			$upload_maxsize=isset($_POST['upload_maxsize'])&&$_POST['upload_maxsize']>0 ? intval($_POST['upload_maxsize'])*1024*1024 : C ( 'UPLOAD_IMG_SIZE' );	 
			$config=array();
			$config['maxSize']=$upload_maxsize;
			$config['rootPath']=UPLOAD_PATH;
			$config['savePath']=$patch . '/';
			$config['saveName']= array ('uniqid', '' );
			$config['exts']=explode(",", $upload_allowext);
			$config['autoSub']=true;
			$config['subName']=date("Y")."/".date("m")."/".date("d");
			$data = array ();
			$thumb = $_FILES [$field];
			$upload = new \Think\Upload ( $config );
			if (! empty ( $thumb ['size'] )) {
					@unlink ( "." . $_POST [$field . "_txt"] ); //删除原先缩略图
					$info = $upload->uploadOne ( $thumb );
					if ($info) {
						$litpic = UPLOAD_PATH . $info ['savepath'] . $info ['savename'];
						chmod ( $litpic, 0777 );
						if (isset($_POST['addwater'])&&$_POST['addwater']==1){ //添加图片水印
							$watermark_config=F("watermark",'',INCLUDE_PATH);
							if ($watermark_config&&$watermark_config['status']==1){
								$image = new \Think\Image();
								$image->open($litpic); // 打开原图
								if (!empty($watermark_config['watermark_img'])){
           							$image ->water(".".$watermark_config['watermark_img'],$watermark_config['watermark_pos'],$watermark_config['watermark_pct'])-> save($litpic,null,$watermark_config['watermark_quality']); //添加水印
								}
								//添加文字水印
								if (!empty($watermark_config['watemard_text'])){
									$image->text($watermark_config['watemard_text'],'.'.$watermark_config['watemard_text_face'],$watermark_config['watemard_text_size'],$watermark_config['watemard_text_color'],$watermark_config['watermark_pos'],$watermark_config['watermark_pospadding'])->save($litpic,null,$watermark_config['watermark_quality']);
								}
							}	
						}
						$jumpurl = str_replace ( "./", "/", $litpic );
						$data['url']=$data [$field] = $jumpurl;
						$data['status']=1;
						$data['info']="上传成功";
					} else {
						$data['status']=0;
						$data['info']="上传失败:".$upload->getError ();
					}
			} else {
					$data['url']=$data [$field] = $_POST [$field . "_txt"];
					$data['status']=1;
					$data['info']="上传成功";
			}
			$data['field']=isset($_POST['field']) ?  addslashes($_POST['field']) : "";
			$data['type']=isset($_POST['type']) ?  addslashes($_POST['type']) : "";
			$this->ajaxReturn($data,'JSON');
		}else{
			$this->error("非法请求");
		}
	}
	
	/**
	 * +-------------------------------------------
	 * 删除图片,不删除数据信息
	 * +-------------------------------------------
	 */
	public function del(){
		if (empty($_POST)){
			$this->error("非法请求");
		}
		@unlink(".".$_POST['url']);
		$data=array();
		$data['status']=1;
		$this->ajaxReturn($data,'JSON');
	}
	/**
	  +-----------------------------------------------------------------------------------------
	 * 打开目录及目录下所有文件
	  +-----------------------------------------------------------------------------------------
	 * @param str $path   待打开目录路径
	  +-----------------------------------------------------------------------------------------
	 * @return bool 返回删除状态
	  +-----------------------------------------------------------------------------------------
	 */
	protected  function list_file($path) {
		$handle = opendir ( $path );
		if ($handle) { 
			while ( false !== ($item = readdir ( $handle )) ) {
				if ($item != "." && $item != ".."){ 
					if (is_dir ( "$path/$item" )){
						$file_array= $this->list_file ( "$path/$item" );
					}else{
						$file_array[]=array('file_path'=>str_replace("./", "/", $path."/".$item),'file_name'=>$item) ;
					}
				}
			}
			closedir ( $handle );
			return $file_array;
		} else {
			return false;
		}
	}
	
	/**
	 * +------------------------------------------
	 * 浏览图库或者文件的地址
	 */
	public function lists(){
		$field=isset($_GET['field'])  ? addslashes($_GET['field'] ) : "";
		$path=isset($_GET['path'])  ? $_GET['path'] : "";
		$type=isset($_GET['type'])  ? $_GET['type'] : "";
		$myid=isset($_GET['myid'])  ? $_GET['myid'] : "";
		$iframe=isset($_GET['iframe'])  ? $_GET['iframe'] : ""; 
	 	$dir_path=UPLOAD_PATH.$path;
	 	if (is_dir($dir_path)){
	 		$file_array=$this->list_file($dir_path);
	 	}
	 	if ($file_array){
	 		$data=array('field'=>$field,'type'=>$type,'status'=>1);
	 		foreach ($file_array as $key=>$value){
	 			$data['url']=$value['file_path'];
	 			//$value['file_data']='{"field":"'.$data['field'].'","type":"'.$data['type'].'","status":'.$data['status'].',"url":"'.$data['url'].'"}';
	 			$value['file_data']=json_encode($data);
	 			$file_array[$key]=$value;
	 		}
	 	}
	 	$this->assign("iframe",$iframe);
	 	$this->assign("field",$field);
	 	$this->assign("type",$type);
	 	$this->assign("myid",$myid);
	 	$this->assign("modueid",$this->moduleId);
	 	$this->assign("file_list",$file_array);
	 	$this->assign("dir_path",$dir_path);
		$this->display();
	}
}