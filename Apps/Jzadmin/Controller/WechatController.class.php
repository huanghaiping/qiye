<?php
namespace Jzadmin\Controller;
class WechatController extends CommonController{
	
	/**
	 * +------------------------------------------------------
	 * 公众号管理页面
	 * +------------------------------------------------------
	 */
	public function index(){
		$uniqid_string=isset($_GET['uniqid']) ?  intval($_GET['uniqid']) : "";
		if (!empty($uniqid_string)){ //生成微信唯一的Token
			echo $this->model_name->getUniqId();
			exit();
		}
		$wechat=$this->model_name->getAllWechat();  
		$this->assign("wechat_list",$wechat);
		$this->display();
	}
	
	
	/**
	 * +------------------------------------------------------
	 * 添加微信公众号
	 * +------------------------------------------------------
	 */
	public function add(){
		if (IS_POST){
			$result = $this->model_name->create($this->model_name->createData($_POST));
			if ($result) {
				$this->model_name->add($result);
				F("WechatList",null);
				$this->success ( "添加成功!", U('index'));
			} else {
				$this->error ( $this->model_name->getError() );
			}
		}else {
			$wechatType=$this->model_name->getWechatType();
			$this->assign("wechat_type",$wechatType);
			$this->assign("method","add");
			$this->assign("wxuniqid",$this->model_name->getUniqId());
			$this->display();
		}
	}
	/**
	 * +------------------------------------------------------
	 * 修改微信公众号
	 * +------------------------------------------------------
	 */
	public function edit(){
		$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : "";
		if (IS_POST){
			$result = $this->model_name->create($this->model_name->createData($_POST));
			if ($result) {
				$this->model_name->where("id=".$id)->save($result);
				F("WechatList",null);
				$this->success ( "修改成功!", U('index'));
			} else {
				$this->error ( $this->model_name->getError() );
			}
		}else {
			if (empty ( $id )) {
				$this->error ( "数据请求错误!" );
			}
			$info = $this->model_name->find ($id);
			$this->assign ( "info", $info );
			$wechatType=$this->model_name->getWechatType();
			$this->assign("wechat_type",$wechatType);
			$this->assign("method","edit");
			$this->display("add");
		}
	}
	/**
	 * +------------------------------------------------------
	 * 删除微信公众号
	 * +------------------------------------------------------
	 */
	public function del(){
		$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$info = $this->model_name->find ($id);
		if (!$info){
			$this->error("数据不存在");
		}
		$result=$this->model_name->where("id=".$id)->delete();
		if ($result){
			@unlink(".".$info['wechat_thumb']);
			F("WechatList",null);
			$this->success("删除成功");
		}else {
			$this->error("删除失败");
		}
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号的接口配置
	 * +------------------------------------------------------
	 */
	public function setting(){
		$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$info = $this->model_name->find ($id);
		if (!$info){
			$this->error("数据不存在");
		}
		$result=$this->model_name->where("id=".$id)->find();
		$this->assign ( "info", $info );
		$this->display();
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号的欢迎语
	 * +------------------------------------------------------
	 */
	public function welcome(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$welcome_model=D("WechatWelcome");
		$welcome_list=$welcome_model->order("id desc")->select();
		if ($welcome_list){
			foreach ($welcome_list as $key=>$value){
				$value['wechat_name']=array_key_exists($value['wechat_id'], $wechat) ? $wechat[$value['wechat_id']]['name'] :"";
				$welcome_list[$key]=$value;
			}
		}
		$this->assign("welcome_list",$welcome_list);
		$this->display();
		 
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号添加欢迎语
	 * +------------------------------------------------------
	 */
	public function welcome_add(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$welcome_model=D("WechatWelcome");
		if (IS_POST){
			$data=array();
			$data['wechat_id']=isset($_POST['wechat_id']) ? intval($_POST['wechat_id']) : "";
			$data['welcome_type']=isset($_POST['welcome_type']) ? intval($_POST['welcome_type']) : "";
			$data['title']=isset($_POST['title']) ? addSlashesFun($_POST['title']) : "";
			$data['content']=isset($_POST['content']) ? addSlashesFun($_POST['content']) : "";
			if (empty($data['content'])){
				$this->error("请输入内容");
			}
			$data['url']=isset($_POST['url']) ? addSlashesFun($_POST['url']) : "";
			$data['ctime']=time();
			$isExistWechatId=$welcome_model->where("wechat_id=".$data['wechat_id'])->count();
			if ($isExistWechatId>0){
				$this->error($wechat[$data['wechat_id']]['name']."已经添加了欢迎语,请勿重复添加");
			}
			$file_data = $this->model_name->uploadImg ( array ('thumb' ), 'wechat' ); 
			if (!empty($file_data['thumb'])){
				$data['thumb'] = $file_data['thumb'];
			}
			$result=$welcome_model->create($data);
			if ($result){
				$welcome_model->add($result);
				$this->success("添加成功",U('welcome'));	
			}else{
				$this->error("添加失败:".$welcome_model->getError());
			}
		}else{
			$welcome_list=$welcome_model->order("id desc")->select();
			if ($welcome_list){
				$wechatIds=getSubByKey($welcome_list,"wechat_id");
				foreach ($wechat as $key=>$value){
					if (in_array($value['id'], $wechatIds)){
						unset($wechat[$key]);
					}
				}
			}
			if (empty($wechat)){
				$this->error("暂无可以添加的公众号",U('welcome'));
			}
			$this->assign("wechat_list",$wechat);
			$this->assign("method","welcome_add");
			$this->display();
		}
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号修改欢迎语
	 * +------------------------------------------------------
	 */
	public function welcome_edit(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
		$welcome_model=D("WechatWelcome");
		if (IS_POST){
			$data=array();
			$data['wechat_id']=isset($_POST['wechat_id']) ? intval($_POST['wechat_id']) : "";
			$data['welcome_type']=isset($_POST['welcome_type']) ? intval($_POST['welcome_type']) : "";
			$data['title']=isset($_POST['title']) ? addSlashesFun($_POST['title']) : "";
			$data['content']=isset($_POST['content']) ? addSlashesFun($_POST['content']) : "";
			if (empty($data['content'])){
				$this->error("请输入内容");
			}
			$data['url']=isset($_POST['url']) ? addSlashesFun($_POST['url']) : "";
			$data['ctime']=time();
			if ($data['wechat_id']!=intval($_POST['wechat_id_old'])){
				$isExistWechatId=$welcome_model->where("wechat_id=".$data['wechat_id'])->count();
				if ($isExistWechatId>0){
					$this->error($wechat[$data['wechat_id']]['name']."已经添加了欢迎语,请勿重复添加");
				}
			}
			$file_data = $this->model_name->uploadImg ( array ('thumb' ), 'wechat' ); 
			if (!empty($file_data['thumb'])){
				$data['thumb'] = $file_data['thumb'];
			}
			$result=$welcome_model->create($data);
			if ($result){
				$welcome_model->where("id=".$id)->save($result);
				$this->success("修改成功",U('welcome'));	
			}else{
				$this->error("修改失败:".$welcome_model->getError());
			}
		}else{
			$info=$welcome_model->where("id=".$id)->find();
			$this->assign("info",$info);
			$this->assign("wechat_list",$wechat);
			$this->assign("method","welcome_edit");
			$this->display("welcome_add");
		}
	}
	/**
	 * +------------------------------------------------------
	 * 删除微信公众号欢迎语
	 * +------------------------------------------------------
	 */
	public function welcome_del(){
		$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$welcome_model=D("WechatWelcome");
		$info = $welcome_model->find ($id);
		if (!$info){
			$this->error("数据不存在");
		}
		$result=$welcome_model->where("id=".$id)->delete();
		if ($result){
			@unlink(".".$info['thumb']);
			$this->success("删除成功");
		}else {
			$this->error("删除失败");
		}
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号之宣传页
	 * +------------------------------------------------------
	 */
	public function page(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$welcome_model=D("WechatPage");
		$welcome_list=$welcome_model->order("id desc")->select();
		if ($welcome_list){
			foreach ($welcome_list as $key=>$value){
				$value['wechat_name']=array_key_exists($value['wechat_id'], $wechat) ? $wechat[$value['wechat_id']]['name'] :"";
				$welcome_list[$key]=$value;
			}
		}
		$this->assign("welcome_list",$welcome_list);
		$this->display();
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号添加宣传页
	 * +------------------------------------------------------
	 */
	public function page_add(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$welcome_model=D("WechatPage");
		if (IS_POST){
			$data=array();
			$data['wechat_id']=isset($_POST['wechat_id']) ? intval($_POST['wechat_id']) : "";
			$data['title']=isset($_POST['title']) ? addSlashesFun($_POST['title']) : "";
			$data['content']=isset($_POST['content']) ? addSlashesFun($_POST['content']) : "";
			if (empty($data['content'])){
				$this->error("请输入内容");
			}
			$data['copyright']=isset($_POST['copyright']) ? addSlashesFun($_POST['copyright']) : "";
			$data['ctime']=time();
			
			$isExistWechatId = $welcome_model->where ( "wechat_id=" . $data ['wechat_id'] )->count ();
			if ($isExistWechatId > 0) {
				$this->error ( $wechat [$data ['wechat_id']] ['name'] . "已经添加了宣传页,请勿重复添加" );
			}

			$file_data = $this->model_name->uploadImg ( array ('thumb' ), 'wechat' ); 
			if (!empty($file_data['thumb'])){
				$data['thumb'] = $file_data['thumb'];
			}
			$result=$welcome_model->create($data);
			if ($result){
				$welcome_model->add($result);
				$this->success("添加成功",U('page'));	
			}else{
				$this->error("添加失败:".$welcome_model->getError());
			}
		}else{
			$welcome_list=$welcome_model->order("id desc")->select();
			if ($welcome_list){
				$wechatIds=getSubByKey($welcome_list,"wechat_id");
				foreach ($wechat as $key=>$value){
					if (in_array($value['id'], $wechatIds)){
						unset($wechat[$key]);
					}
				}
			}
			if (empty($wechat)){
				$this->error("暂无可以添加的公众号",U('welcome'));
			}
			$this->assign("wechat_list",$wechat);
			$this->assign("method","page_add");
			$this->display();
		}
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号修改宣传页
	 * +------------------------------------------------------
	 */
	public function page_edit(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
		$welcome_model=D("WechatPage");
		if (IS_POST){
			$data=array();
			$data['wechat_id']=isset($_POST['wechat_id']) ? intval($_POST['wechat_id']) : "";
			$data['title']=isset($_POST['title']) ? addSlashesFun($_POST['title']) : "";
			$data['content']=isset($_POST['content']) ? addSlashesFun($_POST['content']) : "";
			if (empty($data['content'])){
				$this->error("请输入内容");
			}
			$data['copyright']=isset($_POST['copyright']) ? addSlashesFun($_POST['copyright']) : "";
			$data['ctime']=time();
			if ($data['wechat_id']!=intval($_POST['wechat_id_old'])){
				$isExistWechatId=$welcome_model->where("wechat_id=".$data['wechat_id'])->count();
				if ($isExistWechatId>0){
					$this->error($wechat[$data['wechat_id']]['name']."已经添加了宣传页,请勿重复添加");
				}
			}
			$file_data = $this->model_name->uploadImg ( array ('thumb' ), 'wechat' ); 
			if (!empty($file_data['thumb'])){
				$data['thumb'] = $file_data['thumb'];
			}
			$result=$welcome_model->create($data);
			if ($result){
				$welcome_model->where("id=".$id)->save($result);
				$this->success("修改成功",U('page'));	
			}else{
				$this->error("修改失败:".$welcome_model->getError());
			}
		}else{
			$info=$welcome_model->where("id=".$id)->find();
			$this->assign("info",$info);
			$this->assign("wechat_list",$wechat);
			$this->assign("method","page_edit");
			$this->display("page_add");
		}
	}
	/**
	 * +------------------------------------------------------
	 * 删除微信公众号宣传页
	 * +------------------------------------------------------
	 */
	public function page_del(){
		$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$welcome_model=D("WechatPage");
		$info = $welcome_model->find ($id);
		if (!$info){
			$this->error("数据不存在");
		}
		$result=$welcome_model->where("id=".$id)->delete();
		if ($result){
			@unlink(".".$info['thumb']);
			$this->success("删除成功");
		}else {
			$this->error("删除失败");
		}
	}
	
	/**
	 * +------------------------------------------------------
	 * 微信公众号菜单目录
	 * +------------------------------------------------------
	 */
	public function menu(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$wechat_id=isset($_GET['wechat_id']) ? intval($_GET['wechat_id']) : "";
		if (empty($wechat_id)){
			$wechat_list=array_values($wechat);
			$wechat_id=$wechat_list[0]['id'];
		}
		
		$menu_list=$this->model_name->getMenuType($wechat_id);
		if ($menu_list){
			foreach ($menu_list as $key=>$value){
				$value['wechat_event_name']=$this->model_name->menuType($value['wechat_event']);
				$menu_list[$key]=$value;
			}
		}
		$this->assign("current_num",$wechat[$wechat_id]);
		$this->assign("menu_list",$menu_list);
		$this->assign("wechat_id",$wechat_id);
		$this->assign("wechat_list",$wechat);
		$this->display();	
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号创建菜单
	 * +------------------------------------------------------
	 */
	public function menu_add(){
		$wechat_id=isset($_REQUEST['wechat_id']) ? intval($_REQUEST['wechat_id']) : "";
		if (empty($wechat_id)){
			$this->error("请先添加公众号",U('add'));
		}
		if (IS_POST){
			$data=array();
			$data['menu_name']=isset($_POST['menu_name']) ? addSlashesFun($_POST['menu_name']) : "";
			if (empty($data['menu_name'])){
				$this->error("请输入菜单名称");
			}
			$data['wechat_id']=$wechat_id;
			$data['parent_id']=isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
			$data['wechat_event']=isset($_POST['wechat_event']) ? addSlashesFun($_POST['wechat_event']) : "";
			$data['replay_keyword']=isset($_POST['replay_keyword']) ? addSlashesFun($_POST['replay_keyword']) : "";
			$data['url']=isset($_POST['url']) ? addSlashesFun($_POST['url']) : "";
			$data['sort']=isset($_POST['sort']) ? intval($_POST['sort']) : 0;
			$data['status']=isset($_POST['status']) ? intval($_POST['status']) : 0;
			$data['ctime']=time();
			$WechatMenu=D("WechatMenu");
			$result=$WechatMenu->create($data);
			if ($result){
				$WechatMenu->add($result);
				$this->model_name->updateMenuCache($wechat_id);
				$this->success("添加成功",U('menu',array('wechat_id'=>$wechat_id)));
			}else{
				$this->error("添加失败:".$WechatMenu->getError());
			}
			
		}else{
			$menu_type=$this->model_name->menuType();
			$this->assign("menu_type",$menu_type);
			$category=$this->model_name->getMenuType($wechat_id);
			$this->assign("category_list",$category);
			$this->assign("wechat_id",$wechat_id);
			$this->assign("method","menu_add");
			$this->display();
		}
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号修改菜单
	 * +------------------------------------------------------
	 */
	public function menu_edit(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) : "";
		$WechatMenu=D("WechatMenu");
		 if (IS_POST){
		 $data=array();
			$data['menu_name']=isset($_POST['menu_name']) ? addSlashesFun($_POST['menu_name']) : "";
			if (empty($data['menu_name'])){
				$this->error("请输入菜单名称");
			}
			$data['wechat_id']=isset($_POST['wechat_id']) ? intval($_POST['wechat_id']) : 0;;
			$data['parent_id']=isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
			$data['wechat_event']=isset($_POST['wechat_event']) ? addSlashesFun($_POST['wechat_event']) : "";
			$data['replay_keyword']=isset($_POST['replay_keyword']) ? addSlashesFun($_POST['replay_keyword']) : "";
			$data['url']=isset($_POST['url']) ? addSlashesFun($_POST['url']) : "";
			$data['sort']=isset($_POST['sort']) ? intval($_POST['sort']) : 0;
			$data['status']=isset($_POST['status']) ? intval($_POST['status']) : 0;
			$data['ctime']=time();
			$WechatMenu=D("WechatMenu");
			$result=$WechatMenu->create($data);
			if ($result){
				$WechatMenu->where("id='".$id."'")->save($result);
				$this->model_name->updateMenuCache($data['wechat_id']);
				$this->success("修改成功",U('menu',array('wechat_id'=>$data['wechat_id'])));
			}else{
				$this->error("修改失败:".$WechatMenu->getError());
			}
		 }else{
		 	$info=$WechatMenu->where("id=".$id)->find();
		 	if (!$info){
		 		$this->error("菜单不存在");
		 	}
			$this->assign("info",$info);
		 	$this->assign("method","menu_edit");
		 	$wechat_id=$info['wechat_id'];
		 	$menu_type=$this->model_name->menuType();
			$this->assign("menu_type",$menu_type);
			$category=$this->model_name->getMenuType($wechat_id);
			$this->assign("category_list",$category);
			$this->assign("wechat_id",$wechat_id);
			$this->display("menu_add");
		 }
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号删除菜单
	 * +------------------------------------------------------
	 */
	public function menu_del(){
		$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$WechatMenu=D("WechatMenu");
		$info = $WechatMenu->find ($id);
		if (!$info){
			$this->error("数据不存在");
		}
		$result=$WechatMenu->where("id=".$id)->delete();
		if ($result){
			$this->model_name->updateMenuCache($info['wechat_id']);
			$this->success("删除成功",U('menu',array('wechat_id'=>$info['wechat_id'])));
		}else {
			$this->error("删除失败");
		}
	}
	
	
	/**
	 * +------------------------------------------------------
	 * 微信公众号之用户中心
	 * +------------------------------------------------------
	 */
	public function user(){
		$this->display();
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号之自定义回复
	 * +------------------------------------------------------
	 */
	public function reply(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$reply_type=array(1=>'图文回复',2=>'多图文设置',3=>'文本回复');
		$reply_id= isset ( $_REQUEST ['reply_id'] ) ? intval ( $_REQUEST ['reply_id'] ) : 1;
		$wechat_id=isset($_REQUEST['wechat_id']) ? intval($_REQUEST['wechat_id']) : "";
		if (empty($wechat_id)){
			$wechat_list=array_values($wechat);
			$wechat_id=$wechat_list[0]['id'];
		}
		$field="id,keyword,keyword_type,title,thumb,view_count,mult_ids,listorder, ctime";
		$prefix=C ( "DB_PREFIX" );
		$sql="SELECT ".$field." FROM ".$prefix."wechat_reply where wechat_id='".$wechat_id."' and reply_id='".$reply_id."' order by listorder desc,id desc "; 
		$param=array('reply_id'=>$reply_id,'wechat_id'=>$wechat_id);
		$result = $this->model_name->getPageData ( $sql, $param, 20 );
		if ($result ['data']){
			foreach ($result ['data'] as $key=>$value){
				//$value['keyword_type_name']=$this->model_name->getKeywordType($value['keyword_type']);
				if (!empty($value['mult_ids'])){
					$ids_info=$this->model_name->getMulitReplyList($value['mult_ids']);
					$ids=getSubByKey($ids_info,"id"); 
					$value['text_list']=$this->model_name->getReplyListByIds($ids,'',$ids_info);
				}
				$result ['data'][$key]=$value;
			}
		}
		$this->assign ( "userlist", $result ['data'] );
		$this->assign ( "page", $result ['page'] );
		$this->assign("reply_type",$reply_type);
		$this->assign("reply_id",$reply_id);
		$this->assign("wechat_list",$wechat);
		$this->assign("wechat_id",$wechat_id);
		$this->display("reply_".$reply_id);
	}
	
	/**
	 * +------------------------------------------------------
	 * 微信公众号之添加回复
	 * +------------------------------------------------------
	 */
	public function reply_add(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$reply_id= isset ( $_REQUEST ['reply_id'] ) ? intval ( $_REQUEST ['reply_id'] ) : 1;
		$wechat_id=isset($_REQUEST['wechat_id']) ? intval($_REQUEST['wechat_id']) : "";
		$wechat_reply_model=D("WechatReply");
		if (IS_POST){
			$data=array();
			$data['keyword']=isset($_POST['keyword']) ? addSlashesFun($_POST['keyword']) : "";
			if (empty($data['keyword'])){
				$this->error("请输入关键词");
			}
			if ($reply_id==1){
				$data['title']=isset($_POST['title']) ? addSlashesFun($_POST['title']) : "";
				if (empty($data['title'])){
					$this->error("请输入标题");
				}
				$file_data = $this->model_name->uploadImg ( array ('thumb' ), 'wechat' );
				if (empty ( $file_data ['thumb'] )) {
					$this->error("请上传封面");
				}
				$data ['thumb'] = $file_data ['thumb'];
			}			
			$data['reply_id']=$reply_id;
			$data['wechat_id']=$wechat_id;
			$data['keyword_type']=isset($_POST['keyword_type']) ?  intval($_POST['keyword_type']) : 0;
			$data['content']=isset($_POST['content']) ? addSlashesFun($_POST['content']) : "";
			$data['description']=isset($_POST['description']) ? addSlashesFun($_POST['description']) : "";
			$data['url']=isset($_POST['url']) ? addSlashesFun($_POST['url']) : "";
			$data['mult_ids']=isset($_POST['mult_ids']) ? addSlashesFun($_POST['mult_ids']) : "";
			if (!empty($data['mult_ids'])){
				$data['mult_ids']=implode("#", getSubByKey($this->model_name->getMulitReplyList($data['mult_ids']),"info"));
			}
			$data['ctime']=time();
			$data['listorder']=isset($_POST['listorder']) ?  intval($_POST['listorder']) : 0;
			$result=$wechat_reply_model->create($data);
			if ($result){
				$wechat_reply_model->add($result);
				$this->success("添加成功",U('reply',array('reply_id'=>$reply_id,'wechat_id'=>$wechat_id)));
			}else{
				$this->error("添加失败:".$wechat_reply_model->getError());
			}
		}else{
			$keyword_type=$this->model_name->getKeywordType();
			$this->assign("reply_id",$reply_id);
			$this->assign("wechat_id",$wechat_id);
			$this->assign("keyword_type",$keyword_type);
			$this->assign("method","reply_add");
			if ($reply_id==2){ //当是多图文的时候获取列表
				$reply_list=$this->model_name->replyList($wechat_id);
				$this->assign("reply_list",$reply_list);
			}
			$this->display("reply_add_".$reply_id);
		}
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号之修改回复
	 * +------------------------------------------------------
	 */
	public function reply_edit(){
		$wechat=$this->model_name->getAllWechat();
		if (empty($wechat)){
			$this->error("请先添加公众号",U('add'));
		}
		$id= isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : 0;
		$wechat_reply_model=D("WechatReply");
		if (IS_POST){
			$data=array();
			$reply_id= isset ( $_REQUEST ['reply_id'] ) ? intval ( $_REQUEST ['reply_id'] ) : 1;
			$wechat_id=isset($_REQUEST['wechat_id']) ? intval($_REQUEST['wechat_id']) : "";
			$data['keyword']=isset($_POST['keyword']) ? addSlashesFun($_POST['keyword']) : "";
			if (empty($data['keyword'])){
				$this->error("请输入关键词");
			}
			if ($reply_id==1){
				$data['title']=isset($_POST['title']) ? addSlashesFun($_POST['title']) : "";
				if (empty($data['title'])){
					$this->error("请输入标题");
				}
				$file_data = $this->model_name->uploadImg ( array ('thumb' ), 'wechat' );
				if (empty ( $file_data ['thumb'] )) {
					$this->error("请上传封面");
				}
				$data ['thumb'] = $file_data ['thumb'];
			}
			$wechat_reply_model=D("WechatReply");
			$data['reply_id']=$reply_id;
			$data['wechat_id']=$wechat_id;
			$data['keyword_type']=isset($_POST['keyword_type']) ?  intval($_POST['keyword_type']) : 0;
			$data['content']=isset($_POST['content']) ? addSlashesFun($_POST['content']) : "";
			$data['description']=isset($_POST['description']) ? addSlashesFun($_POST['description']) : "";
			$data['url']=isset($_POST['url']) ? addSlashesFun($_POST['url']) : "";
			$data['mult_ids']=isset($_POST['mult_ids']) ? addSlashesFun($_POST['mult_ids']) : "";
			if (!empty($data['mult_ids'])){
				$data['mult_ids']=implode("#", getSubByKey($this->model_name->getMulitReplyList($data['mult_ids']),"info"));
			}
			$data['ctime']=time();
			$data['listorder']=isset($_POST['listorder']) ?  intval($_POST['listorder']) : 0;
			$result=$wechat_reply_model->create($data);
			if ($result){
				$wechat_reply_model->where("id='".$id."'")->save($result);
				$this->success("修改成功",U('reply',array('reply_id'=>$reply_id,'wechat_id'=>$wechat_id)));
			}else{
				$this->error("修改失败:".$wechat_reply_model->getError());
			}
		}else{
			$info=$wechat_reply_model->find($id);
			if (!$info){
				$this->error("数据不存在");
			}
			$reply_id=$info['reply_id'];
			$wechat_id=$info['wechat_id'];
			$info['content']=isset($info['content']) ? stripslashes($info['content']) : "";
			$keyword_type=$this->model_name->getKeywordType();
			$this->assign("reply_id",$reply_id);
			$this->assign("wechat_id",$wechat_id);
			$this->assign("keyword_type",$keyword_type);
			$this->assign("method","reply_edit");
			if ($reply_id==2){ //当是多图文的时候获取列表
				$reply_list=$this->model_name->replyList($wechat_id);
				$this->assign("reply_list",$reply_list);
			}
			if (!empty($info['mult_ids'])){
				$ids_info=$this->model_name->getMulitReplyList($info['mult_ids']);
				$ids=getSubByKey($ids_info,"id");
				$info['text_list']=$this->model_name->getReplyListByIds($ids,'',$ids_info);
			}
			$this->assign("info",$info);
			$this->display("reply_add_".$reply_id);
		}
	}
	/**
	 * +------------------------------------------------------
	 * 微信公众号之删除回复
	 * +------------------------------------------------------
	 */
	public function reply_del(){
		$id = isset ( $_REQUEST ['id'] ) ? intval ( $_REQUEST ['id'] ) : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$wechat_reply_model=D("WechatReply");
		$info = $wechat_reply_model->find ($id);
		if (!$info){
			$this->error("数据不存在");
		}
		$result=$wechat_reply_model->where("id=".$id)->delete();
		if ($result){
			@unlink(".".$info['thumb']);
			$content = stripslashes ( $info['content'] );
			delContentImg ( $content ); // 删除文章内容里的图片和附件
			$this->success("删除成功",U('reply',array('reply_id'=>$info['reply_id'],'wechat_id'=>$info['wechat_id'])));
		}else {
			$this->error("删除失败");
		}
	}
	
		/**
	 * +------------------------------------------------------
	 * 微信公众号之会员卡
	 * +------------------------------------------------------
	 */
	public function card(){
		$this->display();
	}
}