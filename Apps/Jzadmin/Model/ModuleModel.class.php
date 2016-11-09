<?php
namespace Jzadmin\Model;

/**
 * 
 * 模型的Model页面
 * @author Alan
 *
 */
class ModuleModel extends CommonModel {
	
	protected $_validate =array(
		  array('title','require','模型名称必须',1),
		  array('name','require','模型表名必须',1), 
		  array('name','','模型表已经存在！',0,'unique',1),
		  array('controller_name','require','控制器名称必须',1), 
		  array('controller_name','','控制器名称已经存在！',0,'unique',1),
	);
	
	/**
	 * 获取所有的模型
	 */
	public function getModule(){
		$module_list=F ( 'MODULE_LIST');
		if(!$module_list){
			$row = $this->where ( "status=1" )->order ( "id desc" )->select ();
			$module_list=array();
			foreach ($row as $value){
				$module_list[$value['id']]=$value;
			}
			F ( 'MODULE_LIST', $module_list );
		}
		return $module_list;
	}
	
	/**
	 * 
	 * 根据模型ID获取对应的模型信息
	 * 
	 * @param unknown_type $controllerName
	 */
	public function getModuleIdByModuleId($moduleId){
		if (empty($moduleId)) return false;
		$module_list=$this->getModule();
		$row=array();
		foreach ($module_list as $value){
			if ($value['id']==$moduleId){
				$row=$value;
				break;
			}
		}
		return $row;
	}
	
	/**
	 * 根据批量模型ID获取对应模型信息
	 * 
	 * @param string	$moduleIds		模型IDS,多个用英文逗号隔开
	 * @return Array	$row
	 */
	public function getModuleIdByModuleIds($moduleIds){
		if (empty($moduleIds)) return false;
		if (!is_array($moduleIds)) $moduleIds=explode(",", $moduleIds);
		$module_list=$this->getModule();
		$moduleIds=array_filter(array_unique($moduleIds));
		$row=array();
		foreach ($module_list as $value){
			if (in_array($value['id'], $moduleIds)){
				$row[$value['id']]=$value;
			}
		}
		return $row;
	}
	
	/**
	 * 保存项目的路由文件
	 * 
	 * URL路由规则之封面
	 * article-1=>article/index/id/1
	   article-1-2=>article/index/id/1/p/2 分页模式
	 * URL路由规则之列表页
	 * 	article_1=>article/lists/id/1
		article_1_2=>article/lists/id/1/p/2 分页模式
	 * URL路由规则之内容页
		article/1 =>article/detail/id/1
		article/1/2=>article/detail/id/1/p/2 分页模式
	 */
	public function createRoutes($lang_list=array()){
		if (empty($lang_list)){
			$lang_model=D("Lang");
			$lang_list=$lang_model->getLang();
		}
		$rounte=array();
		$lang_string=$lang_list ? implode("|", array_keys($lang_list)) : "";
		$rounte['/^('.$lang_string.')$/']='Index/index?l=:1';
		$moduleList=$this->getModule();
		foreach ($moduleList as $key=>$value){
			$controName2tolower=strtolower($value['controller_name']);
			$rounte['/^'.$controName2tolower.'-(\d+)$/']			=$value['controller_name'].'/index?id=:1&';
			$rounte['/^'.$controName2tolower.'-(\d+)-(\d+)$/']		=$value['controller_name'].'/index?id=:1&p=:2&';
			
			$rounte['/^'.$controName2tolower.'_(\d+)$/']			=$value['controller_name'].'/lists?id=:1&';
			$rounte['/^'.$controName2tolower.'_(\d+)_(\d+)$/']		=$value['controller_name'].'/lists?id=:1&p=:2&';
			
			$rounte['/^'.$controName2tolower.'\/(\d+)$/']			=$value['controller_name'].'/detail?id=:1&';
			$rounte['/^'.$controName2tolower.'\/(\d+)\/(\d+)$/']	=$value['controller_name'].'/detail/id/:1/p/:2';				
		}
		$rounte['/^([\w^_]+)\/(\d+)_(\d+)$/']='EmptyUrl/detail?catdir=:1&id=:2&p=:3&';
		$rounte['/^([\w^_]+)\/(\d+)$/']='EmptyUrl/detail?catdir=:1&id=:2&';
		$rounte['/^([\w^_]+)_(\d+)$/']='EmptyUrl/emptys?catdir=:1&p=:2&';
		$rounte['/^([\w^_]+)$/']='EmptyUrl/emptys?catdir=:1&';

		F("Routes",$rounte,INCLUDE_PATH);
	}
}