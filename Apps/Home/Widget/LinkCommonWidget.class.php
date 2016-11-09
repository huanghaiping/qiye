<?php
namespace Home\Widget;
use Think\Controller;
class LinkCommonWidget  extends Controller {
	
	/**
	 * 获取公共链接库的内容
	 * Enter description here ...
	 */
	public function flink(){
		$link_info=F("CommonLink",'', INCLUDE_PATH);//var_dump($link_info);
		if (!$link_info){
			return '';
		}
   		$link_common_model=D(ADMIN_NAME."/Link");
   		$cache_s=S("CommonLink");
   		if (!$cache_s){
   			$service_info_url= $link_common_model->getUrl ( 3 );//提交/修改链接的URL	
   			if ($service_info_url){
   				$param=array('linkId'=>$link_info['linkId'],'siteurl'=>$link_info['siteurl']);
   				$result=$link_common_model->http($service_info_url,$param);
   				$service_info = $link_common_model->json_to_array ( json_decode ( $result ) );
				if ($service_info ['status']) {
					S ( "CommonLink", $service_info['data'] , array ('expire' => 43200 ) );
					$link_info=$service_info['data'];
					F("CommonLink",$service_info['data'], INCLUDE_PATH);
				}
   			}
   		}
		if ($link_info['is_release']!=1){
			return '';
		}
		if ($link_info['return_status']!=1){
			return '';
		}
		$time=time(); //判断是否过期
	 	if (! empty ( $link_info ['end_time'] )) {
	        if ($time > $link_info ['end_time']) {
	           return '';
	        }  
   		}
   		
		$link_list = S ( "link_list" );
		if (!$link_list){
			$param=array('linkId'=>$link_info['linkId'],'siteurl'=>$link_info['siteurl'],'token'=>$link_info['token']);
			$link_list=$link_common_model->getLink($param);
			if (!$link_list){
				return '';
			}
		}
		if ($link_list){
			foreach ($link_list as $key=>$value){
				$link_list[$key]= $link_common_model->json_to_array ( $value );
			}
		}
		$this->assign("flink_list",$link_list);
        $this->display("Common:flink");
    }
 }