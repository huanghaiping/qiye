<?php
namespace Think\Template\TagLib;
use Think\Template\TagLib;
class Vo extends TagLib {
	
	protected $lang = '$lang'; //语言模板变量
	protected $lang_set=LANG_SET;
	// 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） adid 广告标识 
	protected $tags = array (
		'list' => array ('attr' => 'name,field,limit,order,catid,posid,where,sql,key,mod,ids,status,result', 'level' => 3 ),
		'subcat'=>array('attr'=>'catid,type,self,key,id','level'=>3),
		'block' => array('attr'=>'adid,result,field','close'=>1),
		'nav'=> array('attr'=>'catid,id,position','close'=>1),
		'navcontent'=> array('attr'=>'catid,id','close'=>1),
		'crumbs' => array('attr'=>'id,catid,space','close'=>1),//面包屑
		'link' => array('attr'=>'linktype,field,limit,order','level'=>3),
		'kefu' => array('attr'=>'typeid,field,id','close'=>1),
	);
	
	/**
	 * 查询列表的内容标签
	 * 
	 * @param  Array		$tag	参数选择
	 * @param  String   $content	前台内容模板
	 * @return String	$parsestr	返回组装后的数据
	 */
	public function _list($tag, $content) { 
		
		$id = ! empty ( $tag ['result'] ) ? $tag ['result'] : str_replace("_", '', __FUNCTION__); //定义数据查询的结果存放变量
		$key = ! empty ( $tag ['key'] ) ? $tag ['key'] : 'i';
		$page = ! empty ( $tag ['page'] ) ? '1' : '0';
		$mod = isset ( $tag ['mod'] ) ? $tag ['mod'] : '2';
		
		if ($tag ['name'] || $tag ['catid']) { //组装用户输入的值拼接查询条件
			$sql = '';
			$module = $tag ['name'];
			$order = isset ( $tag ['order'] ) ? $tag ['order'] : 'id desc';
			$field = isset ( $tag ['field'] ) ? $tag ['field'] : 'id,catid,title,keyword,description,thumb,createtime';
			$where = isset ( $tag ['where'] ) ? $tag ['where'] : ' 1 ';
			$limit = isset ( $tag ['limit'] ) ? $tag ['limit'] : '10';
			$status = isset ( $tag ['status'] ) ? intval ( $tag ['status'] ) : '0';
			$ids = isset ( $tag ['ids'] ) ? $tag ['ids'] : '';
			if ($this->lang_set) {
				$where .= " and lang='" . $this->lang."'";
			}
			if ($ids) {
				if (strpos ( $ids, ',' )) {
					$where .= " AND id in($ids) ";
				} else {
					$where .= " AND id=$ids ";
				}
			}
			$where .= " AND status=$status ";
			if ($tag ['catid']) {
				$onezm = substr ( $tag ['catid'], 0, 1 ); 
				$catid = $tag ['catid'];
				if (is_numeric ( $catid )) {
					
					$menu_model=D("Menu");
					$category_arr =$menu_model->getMenuById($catid);
					if ($category_arr){
						$module = $category_arr [$catid] ['model_name'];
					}else{
						return '';
					}
					if ($category_arr [$catid] ['arcchild']) {
						$where .= " AND catid in(" . $category_arr [$catid] ['arcchild'] . ")";
					} else {
						$where .= " AND catid=" . $catid;
					}
				} elseif ($onezm == '$') {
					$where .= ' AND catid in(' . $tag ['catid'] . ')';
				} else {
					$where .= ' AND catid in(' . strip_tags ( $tag ['catid'] ) . ')';
				}
			}
			
			if ($tag ['posid']) {
				$posid = $tag ['posid'];
				if (is_numeric ( $posid )) {
					$where .= '  AND posid =' . $posid;
				} else {
					$where .= ' AND posid in(' . strip_tags ( $posid ) . ')';
				}
			}
			$sql = "M(\"{$module}\")->field(\"{$field}\")->where(\"{$where}\")->order(\"{$order}\")->limit(\"{$limit}\")->select();";
		} else {
			if (! $tag ['sql'])
				return ''; //排除没有指定model名称，也没有指定sql语句的情况
			$sql = "M()->query(\"{$tag['sql']}\")";
		}
		
		//下面拼接输出语句
		$parsestr = '';
		$parsestr .= '<?php if(empty($categorys)){ $categorys=D(\'Menu\')->getAllMenu(); }  $param=array();';
		$parsestr .= ' try {  $_result=' . $sql . '; } catch (\Exception $e) { echo  $e->getMessage();} if ($_result): $' . $key . '=0;';
		$parsestr .= 'foreach($_result as $key=>$' . $id . '):';
		$parsestr .='if(isset($' . $id . '["jump_url"])&&!empty($' . $id . '["jump_url"])){ $param["jump_url"]=$' . $id . '["jump_url"]; }';
		$parsestr .='$' . $id . '["url"]=createHomeUrl($categorys[$' . $id . '["catid"]],$' . $id . '["id"],$param);';
		$parsestr .= '++$' . $key . ';$mod = ($' . $key . ' % ' . $mod . ' );?>';
		$parsestr .= $content; //解析在article标签中的内容
		$parsestr .= '<?php endforeach; endif;?>';
		return $parsestr;
	}
	
	/**
	 * 获取下级栏目内容
	 * 
	 * @param Array 	$tag		解析的参数说明
	 * @param String 	$content	解析的模板
	 */
	public function _subcat($tag,$content) {
		$id = !empty($tag['id'])?$tag['id']:str_replace("_", '', __FUNCTION__); //定义数据查询的结果存放变量 result
		$type = !empty($tag['type'])?$tag['type']:'';
		$self = !empty($tag['self'])?'1':'';
		$key    = !empty($tag['key'])?$tag['key']:'n';
		$catid = !empty($tag['catid'])? $tag['catid'] : '0';
		if($type) $condition = ' &&  $'.$id.'["typeid"] == "'.$type.'"';
		
		if($self){ $ifleft = '('; $selfcondition = ') or ( intval('.$catid.')==$'.$id.'["id"])';}
		$parsestr='';
		$parsestr .= '<?php if(empty($categorys)){ $categorys=D(\'Menu\')->getAllMenu(); } $'.$key.'=0;';
		$parsestr .= 'foreach($categorys as $key=>$'.$id.'):';
		$parsestr .= 'if($'.$id.'["status"]==0){ continue; }';
		$parsestr .= 'if('.$ifleft.' intval('.$catid.')==$'.$id.'["parent_id"] '.$condition.$selfcondition.' ) :';
		$parsestr .= '++$'.$key.';?>';
		$parsestr .= $content;
		$parsestr .= '<?php endif;?>';
		$parsestr .= '<?php endforeach;?>';
		return  $parsestr;
		
	}
	
	/**
	 * 获取碎片管理内容
	 * 
	 * @param Array 	$tag		解析的参数说明
	 * @param String 	$content	解析的模板
	 */
	public function _block($tag, $content) {
		$map="";
		$result = ! empty ( $tag ['result'] ) ? $tag ['result'] : str_replace("_", '', __FUNCTION__);
		$map .= ($tag ['adid']) ? " pos ='".$tag['adid']."' and lang='".$this->lang."'" : "'";
		$sql = "M('Block')->";
		$sql .= "where(\"{$map}\")->";
		$sql .= ($tag ['field']) ? "field('{$tag['field']}')->" : "";
		$sql .= "select()";
		//下面拼接输出语句
		$parsestr = '<?php $_result=' . $sql . '; ';
		$parsestr .= 'foreach($_result as $key=>$' . $result . '): ';
		$parsestr .= '$' . $result . '["content"]=isset($' . $result . '["content"]) ? stripslashes($' . $result . '["content"]) : ""; ?>'; 
		$parsestr .= $content; //解析在article标签中的内容
		$parsestr .= '<?php endforeach;?>';
		return $parsestr;
	}
	
	/**
	 * 获取栏目管理内容
	 * 
	 * @param Array 	$tag		解析的参数说明
	 * @param String 	$content	解析的模板
	 */
	public function _nav($tag, $content){
		$id = !empty($tag['id'])? $tag['id'] : str_replace("_", '', __FUNCTION__);
		$catid = !empty($tag['catid'])? $tag['catid'] : '0';
		$position=$tag['position']!="" ? $tag['position'] : "";
		$parsestr='';
		if($position!=""){ $ifleft = '('; $selfcondition = ') and ( '.$position.'==$'.$id.'["position"])';}
		$parsestr .= '<?php if(empty($categorys)){ $categorys=D(\'Menu\')->getAllMenu();  }';
		$parsestr .= 'foreach($categorys as $key=>$'.$id.'):';
		$parsestr .= 'if($'.$id.'["status"]==0){ continue; }';
		$parsestr .= 'if( '.$ifleft.'  intval('.$catid.')==$'.$id.'["parent_id"] '.$selfcondition.' ) :';
		$parsestr .= '?>';
		$parsestr .= $content;
		$parsestr .= '<?php endif;?>';
		$parsestr .= '<?php endforeach;?>';
		return  $parsestr;
	}
	/**
	 * 获取单个内容栏目管理内容
	 * 
	 * @param Array 	$tag		解析的参数说明
	 * @param String 	$content	解析的模板
	 */
	public function _navcontent($tag, $content){
		$result  = !empty($tag['id'])? $tag['id'] : str_replace("_", '', __FUNCTION__);
		if(empty($tag ['catid'])) return '';
		if(is_numeric($tag ['catid'])){
			$map .= ($tag ['catid']) ? " menu_id ='".$tag['catid']."'" : "'";
		}else{
			$map .= ($tag ['catid']) ? " menu_id ='".$tag['catid']."'" : "'";
		}
		$sql = "D('MenuInfo')->";
		$sql .= "where(\"{$map}\")->";
		$sql .= "field('content')->";
		$sql .= "select()";
		//下面拼接输出语句
		$parsestr = '<?php $_result=' . $sql . '; ';
		$parsestr .= 'foreach($_result as $key=>$' . $result . '): ';
		$parsestr .= '$' . $result . '["content"]=isset($' . $result . '["content"]) ? stripslashes($' . $result . '["content"]) : ""; ?>'; 
		$parsestr .= $content; //解析在article标签中的内容
		$parsestr .= '<?php endforeach;?>';
		return $parsestr;
		
	}
	/**
	 * 栏目面包屑导航
	 * 
	 * @param Array 	$tag		解析的参数说明
	 * @param String 	$content	解析的模板
	 */
	public function _crumbs($tag ,$content) {
		$catid		= !empty($tag['catid']) ? $tag['catid'] : '';
		$id = !empty($tag['id'])? $tag['id'] : str_replace("_", '', __FUNCTION__);
		$space		= !empty($tag['space']) ? $tag['space'] : ' > ';
		//下面拼接输出语句
		$parsestr  = '';
		$parsestr .= '<?php if(empty($categorys)){ $categorys=D(\'Menu\')->getAllMenu(); } ';
		$parsestr .= '$count=count($categorys[$'.$catid.'][\'parent_list\']); $crumbs_i=0;';
		$parsestr .= 'foreach($categorys[$'.$catid.'][\'parent_list\'] as $key=>$'.$id.'):';
		$parsestr .= ' $'.$id.'["end"]=($crumbs_i==$count-1) ? 1 : 0 ?>';
		$parsestr .= $content;
		$parsestr .= '<?php $crumbs_i++;?>';
		$parsestr .= '<?php endforeach;?>';
		return  $parsestr;
	}
	/**
	 * 友情链接导航
	 * 
	 * @param Array 	$tag		解析的参数说明
	 * @param String 	$content	解析的模板
	 */
	public function _link($tag,$content) {
		$id = !empty($tag['id'])?$tag['id']:str_replace("_", '', __FUNCTION__);  //定义数据查询的结果存放变量
		$key    = !empty($tag['key'])?$tag['key']:'i';
		$mod    = isset($tag['mod'])?$tag['mod']:'2';
		
		$linktype    = isset($tag['linktype'])?$tag['linktype']:'';
		$order  = isset($tag['order'])?$tag['order']:'id desc';
		$limit  = isset($tag['limit'])?$tag['limit']: '10';
		$field  = isset($tag['field'])?$tag['field']:'*';
		$where = " status = 1 and lang='".$this->lang."'";

		if($linktype){
			$where .=  " and  linktype=".$linktype;
		}
		$sql  = "D(\"Link\")->field(\"{$field}\")->where(\"{$where}\")->order(\"{$order}\")->limit(\"{$limit}\")->select();";
		 
		//下面拼接输出语句
		$parsestr  = '';
		$parsestr .= '<?php  $_result='.$sql.'; if ($_result): $'.$key.'=0;';
		$parsestr .= 'foreach($_result as $key=>$'.$id.'):';
		$parsestr .= '++$'.$key.';$mod = ($'.$key.' % '.$mod.' );?>';
		$parsestr .= $content;//解析在article标签中的内容
		$parsestr .= '<?php endforeach; endif;?>';
		return  $parsestr;
	}
	
	/**
	 * 获取客服管理内容
	 * 
	 * @param Array 	$tag		解析的参数说明
	 * @param String 	$content	解析的模板
	 */
	public function _kefu($tag, $content) {
		$map="";
		$result = ! empty ( $tag ['id'] ) ? $tag ['id'] : str_replace("_", '', __FUNCTION__);
		$map .= ($tag ['typeid']) ? " typeid ='".$tag['typeid']."' and status =1 and lang='".$this->lang."'" : " status =1 and lang='".$this->lang."'";
		$sql = "M('Kefu')->";
		$sql .= "where(\"{$map}\")->";
		$sql .= ($tag ['field']) ? "field('{$tag['field']}')->" : "";
		$sql .= "select()";
		//下面拼接输出语句
		$parsestr = '<?php $_result=' . $sql . '; ';
		$parsestr .= 'foreach($_result as $key=>$' . $result . '): ';
		$parsestr .= '$' . $result . '["content"]=isset($' . $result . '["content"]) ? stripslashes($' . $result . '["content"]) : ""; ?>'; 
		$parsestr .= $content; //解析在article标签中的内容
		$parsestr .= '<?php endforeach;?>';
		return $parsestr;
	}
	

}