<?php
namespace Home\Controller;
class SearchController extends CommonController {
    public function index(){
		$keyword=isset($_REQUEST['keyword']) ? addSlashesFun($_REQUEST['keyword']) : "";
		if(empty($keyword)){
			$this->error("请输入关键词",U('Index/index'));	
		}
		$menu_model = D ( "Menu" );
		$temp = $menu_model->getCategory ( array ("lang" => $this->lang ) );
		$row=$map=$list=$model_name=array();
		if ($temp) {
			$module_model_name = D ( ADMIN_NAME."/Module" );
			$moduleIds = $module_model_name->getModuleIdByModuleIds ( getSubByKey ( $temp, "typeid" ) );
			foreach ( $temp as $key => $value ) {
				if ($value['typeid']!=0){
					$value ['module_name'] = array_key_exists ( $value ['typeid'], $moduleIds ) ? $moduleIds [$value ['typeid']] : array ();
					$temp [$key] = $value;
					$row[$value['id']]=$value;
				}else{
					unset($temp [$key]);
				}
			}
			 
			$map ['status'] = array ('eq', 0 );
			$map ['title']=array('like','%'.$keyword.'%');
			foreach ( $temp as $key => $value ) {
					if(isset($model_name[$value ['model_name']])) continue;
					$result = D ( $value ['model_name'] )->where ( $map )->order ( 'id desc' )->field("id,catid,title,description,createtime")->select ();	
					if ($result) {
						foreach ( $result as $v ) {
							$param=isset($v['jump_url'])&&!empty($v['jump_url']) ? array('jump_url'=>$v['jump_url']):array();
							$url= createHomeUrl ( $row[$v['catid']],$v['id'] ,$param);
							$title=isset($v['title']) ? $v['title'] : "";
							$createtime=isset($v['createtime']) ? $v['createtime'] : "";
							$description=isset($v['description']) ? $v['description'] : "";
							if(!empty($title)){		
								$model_name[$value ['model_name']]=1;	
								$list[]=array('title'=>$title,'url'=>$url,'createtime'=>$createtime,'description'=>$description);
							}
						}
					}
			}
			$init_count=count($list);
			if($init_count>0){
				$createDate=array();
				foreach ($list as $key=>$value){
					$createDate[$key]=$value['createtime'];
				}
				 array_multisort($createDate,SORT_DESC,$createtime);
				 unset($createDate);	
			}
			$arrParams = array ("keyword" => $keyword );
			$listRows =C ( 'PAGE_LISTROWS' );
			$page = new \Think\Page ($init_count, $listRows );
			$page->parameter = ! empty ( $arrParams ) ? http_build_query ( $arrParams ) : '';;
			$pages = $page->fshow ();
			$list = array_slice ( $list, $page->firstRow, $page->listRows );
			
			$this->assign("keyword",$keyword);
			$this->assign("list",$list);
			$this->assign ( "pages", $pages );
		}else{
			$this->error("暂无搜索内容");	
		}
    	$this->get_seo_info(array('site_title'=>'在线搜索'));
        $this->display();
    }
}