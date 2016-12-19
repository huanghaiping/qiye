<?php
/**
 * 
 * Empty (空模块)
 *
 */
namespace Home\Controller;
use Think\Controller;
class EmptyController extends CommonController {
	
	/**
	 * +------------------------------------------------
	 * 空操作项目
	 * +------------------------------------------------
	 */
	public function emptys() {
		//空操作 空模块
		$catid = isset ( $_GET ['catid'] ) ? intval ( $_GET ['catid'] ) : "";
		$catdir = isset ( $_GET ['catdir'] ) ? $_GET ['catdir'] : "";
		$Cat = F ( 'Cat_' . $this->lang, '', INCLUDE_PATH );
		$catid = $catid ? $catid : $Cat [$catdir];
		if (! isset ( $this->categorys [$catid] )) {
			E ('非法操作','404' ); 
		}
		$catInfo = $this->categorys [$catid];
		$method=$catInfo ['listtype'] ? 'index' : 'lists';
		$model  =  A($catInfo['model_name']);
		if (!$model){
			$model=$this;
		}
		$model->$method($catid);
	}
	
	/**
	 * +------------------------------------------------
	 * 项目的频道首页
	 * +------------------------------------------------
	 */
	public function index($catid = '', $module = '') {
		if (isset ( $_GET ['id'] ))
			$catid = intval ( $_GET ['id'] );
		if ($catid) {
			if (isset ( $this->categorys [$catid] ['parent_list'] ) && ! empty ( $this->categorys [$catid] ['parent_list'] )) {
				$topid_info = array_values ( $this->categorys [$catid] ['parent_list'] );
				$topid = $topid_info [0] ['id'];
			} else {
				$topid = intval ( $catid );
			}
			$catInfo = $this->categorys [$catid];
			if (empty ( $module ))
				$module = $catInfo ['model_name'];
			$this->assign ( "catInfo", $catInfo );
			$this->assign ( 'catid', $catid );
			$this->assign ( 'topid', $topid );
		}else{
			if (! isset ( $this->categorys [$catid] )) {
				E ('非法操作','404' ); 
			}
		}
		 
		//获取SEO
		$seoInfo=array('site_title'=>empty($catInfo['site_title']) ? $catInfo['title']:$catInfo['site_title'],'site_keyword'=>$catInfo['site_title'],'site_description'=>$catInfo['site_title']);
		$this->get_seo_info($seoInfo);
		$template= empty($catInfo['template_list']) ? $module.":index":$catInfo['template_list'];
		$this->display ( $template);
	}
	
	/**
	 * +------------------------------------------------
	 * 项目的频道的列表页
	 * +------------------------------------------------
	 */
	public function lists($catid = '', $module = '') {
		if (isset ( $_GET ['id'] ))
			$catid = intval ( $_GET ['id'] );
		if ($catid) {
			if (isset ( $this->categorys [$catid] ['parent_list'] ) && ! empty ( $this->categorys [$catid] ['parent_list'] )) {
				$topid_info = array_values ( $this->categorys [$catid] ['parent_list'] );
				$topid = $topid_info [0] ['id'];
			} else {
				$topid = intval ( $catid );
			}
			$catInfo = $this->categorys [$catid];
			if (empty ( $module ))
				$module = $catInfo ['model_name'];
			$this->assign ( "catInfo", $catInfo );
			$this->assign ( 'catid', $catid );
			$this->assign ( 'topid', $topid );
		}else{
			if (! isset ( $this->categorys [$catid] )) {
				E ('非法操作','404' ); 
			}
		}
		//获取列表
		$where = " status=0 ";
		if($catInfo['arcchild']){							
			$where .= " and catid in(".$catInfo['arcchild'].")";			
		}else{
			$where .=  " and catid=".$catid;			
		}
		$module_name = D ( $module );
		$count = $module_name->where ( $where )->count ();
		if ($count) {
			$listRows = ! empty ( $catInfo ['pagesize'] ) ? $catInfo ['pagesize'] : C ( 'PAGE_LISTROWS' );
			$page = new \Think\Page ( $count, $listRows );
			$pages = $page->show ($catInfo);
			$module_model=D(ADMIN_NAME."/Module");
			$module_info=$module_model->getModuleIdByModuleId($catInfo['typeid']);
			$field = $module_info ['listfields'];
			$field = $field ? $field : 'id,catid,title,keyword,description,thumb,createtime,hits';
			$list = $module_name->field ( $field )->where ( $where )->order ( 'listorder desc,id desc' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
			foreach ($list as $key=>$value){
				$value['url']=createHomeUrl($this->categorys[$value['catid']],$value['id']);
				$list[$key]=$value;
			}
			$this->assign ( 'pages', $pages );
			$this->assign ( 'list', $list );
		}
		
		//获取SEO
		$seoInfo=array('site_title'=>empty($catInfo['site_title']) ? $catInfo['title']:$catInfo['site_title'],'site_keyword'=>$catInfo['site_title'],'site_description'=>$catInfo['site_title']);
		$this->get_seo_info($seoInfo);
		$template= empty($catInfo['template_list']) ? $module.":lists":$catInfo['template_list'];
		$this->display ( $template);
	}
	
	/**
	 * +------------------------------------------------
	 * 项目的频道详细页
	 * +------------------------------------------------
	 */
	public function detail($id='',$module='') { 
		$catdir = isset ( $_GET ['catdir'] ) ? $_GET ['catdir'] : "";
		$id = $id ? $id : intval($_GET['id']);
		if (!empty($catdir)){
			$Cat = F ( 'Cat_' . $this->lang, '', INCLUDE_PATH );
			$catid = $catid ? $catid : $Cat [$catdir];
			if (! isset ( $this->categorys [$catid] )) {
				E ('非法操作','404' ); 
			}
			$catInfo = $this->categorys [$catid];
			$module = $module ? $module : $catInfo['model_name'];
		}else{
			$module = $module ? $module : CONTROLLER_NAME;
		}
		$this->assign('module_name',$module);
		$model= D($module);
		$data = $model->find($id);
		if (!$data){
			$this->error("内容不存在");
		}
		$catid=$data['catid'];
		$catInfo = $this->categorys [$catid];
		
		$model->where("id=".$id)->setInc('hits'); //添加点击次数
		if (isset ( $catInfo ['parent_list'] ) && ! empty ( $catInfo['parent_list'] )) {
			$topid_info = array_values ( $catInfo['parent_list'] );
			$topid = $topid_info [0] ['id'];
		} else {
			$topid = intval ( $catid );
		}
		$seoInfo=array('site_title'=>$data['title'],'site_keyword'=>$data['keyword'],'site_description'=>$data['description']);
		$this->get_seo_info($seoInfo);
		if(!empty($data['template'])){
			$template=$data['template'];
		}else{
			$template=empty($catInfo['template_show']) ? $module.":detail":$catInfo['template_show'];
		}
		$this->assign ($data);
		$this->assign('catid',$catid);
		$this->assign('topid',$topid);
		
		$next=$model->field('title,id')->where(' id>'.$id." and catid = '".$catid."' ")->order('id asc')->limit('1')->find();
		if ($next){
			$next['url']=createHomeUrl($this->categorys[$catid],$next['id']);
		}
		$pre=$model->field('title,id')->where(' id<'.$id." and catid = '".$catid."'")->order('id desc')->limit('1')->find();
		if ($pre){
			$pre['url']=createHomeUrl($this->categorys[$catid],$pre['id']);
		}
		$this->assign('next',$next);
        $this->assign('pre',$pre);
		$this->display ($template);
	}

}
?>