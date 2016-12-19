<?php
namespace Think\Template\TagLib;
use Think\Template\TagLib;
class Ad extends TagLib {
	
	protected $lang = '$lang'; //语言模板变量
	// 标签定义： attr 属性列表 close 是否闭合（0 或者1 默认1） adid 广告标识 
	protected $tags = array (
		"text" => array ('attr' => 'id,result,field', 'close' => 0 ), 
		"image" => array ('attr' => 'id,result,field', 'close' => 1 ), 
		"slide" => array ('attr' => 'id,result,field,limit', 'close' => 1 ), 
		"code" => array ('attr' => 'id,result,field', 'close' => 0 ), 
		"flash" => array ('attr' => 'id,result,field', 'close' => 1 ) 
	);
	
	/**
	 * 文字广告标签
	 * 
	 * @param  Array 	$tag		参数选择
	 * @return String	$parsestr	返回组装后的数据
	 */
	public function _text($tag) {
		$map = "";
		$result = ! empty ( $tag ['result'] ) ? $tag ['result'] : str_replace("_", '', __FUNCTION__);
		$map .= ($tag ['id']) ? " adid='{$tag['id']}' and lang='" . $this->lang . "'" : "'";
		$sql = "D('Ad')->";
		$sql .= "where(\"{$map}\")->";
		$sql .= ($tag ['field']) ? "field('{$tag['field']}')->" : "";
		$sql .= "find()";
		//下面拼接输出语句
		$parsestr = '<?php $' . $result . '=$_result=' . $sql . '; ';
		$parsestr .= ' echo "<a href=".$' . $result . '[\'url\']." target=\'_blank\'>".stripslashes($' . $result . '[\'normbody\'])."</a>";  ?>';
		return $parsestr;
	}
	
	/**
	 * 单张图片的广告标签
	 * 
	 * @param  Array 	$tag		参数选择
	 * @param  String   $content	前台内容模板
	 * @return String	$parsestr	返回组装后的数据
	 */
	public function _image($tag, $content) {
		$map = "";
		$result = ! empty ( $tag ['result'] ) ? $tag ['result'] : str_replace("_", '', __FUNCTION__) ;
		$map .= ($tag ['id']) ? " adid='{$tag['id']}' and lang='" . $this->lang . "'" : "'";
		$sql = "D('Ad')->";
		$sql .= "where(\"{$map}\")->";
		$sql .= ($tag ['field']) ? "field('{$tag['field']}')->" : "";
		$sql .= "select()";
		//下面拼接输出语句
		$parsestr = '<?php $' . $result . '=$_result=' . $sql . '; ';
		$parsestr .= 'foreach($_result as $key=>$' . $result . '):?>';
		$parsestr .= $content; //解析在article标签中的内容
		$parsestr .= '<?php endforeach;?>';
		return $parsestr;
	}
	
	/**
	 * 多张图片幻灯片广告标签
	 * 
	 * @param  Array 	$tag		参数选择
	 * @param  String   $content	前台内容模板
	 * @return String	$parsestr	返回组装后的数据
	 */
	public function _slide($tag, $content) {
		$map = "";
		$result = ! empty ( $tag ['result'] ) ? $tag ['result'] : str_replace("_", '', __FUNCTION__);
		$map .= ($tag ['id']) ? " adid ='{$tag['id']}' and lang='" . $this->lang. "' " : "'";
		$info="info";
		$info_sql = '$'.$info.'=D ( "Ad" )->where ( "'.$map.'" )->field ( "id" )->limit ( 1 )->find ();';
		 
		$sql = "D('Slide')->";
		$sql .= "where(\"typeid='\".$".$info."['id'].\"'\")->";
		$sql .= ($tag ['field']) ? "field('{$tag['field']}')->" : "";
		$sql .= ($tag ['limit']) ? "limit('{$tag['limit']}')->" : "";
		$sql .= "order('sortslide desc ,id desc')->select()";
		
		//下面拼接输出语句
		$parsestr = '<?php '.$info_sql.' if($'.$info.'){  $' . $result . '=$_result=' . $sql . '; ';
		$parsestr .= 'foreach($_result as $key=>$' . $result . '):?>';
		$parsestr .= $content; //解析在article标签中的内容
		$parsestr .= '<?php endforeach; } ?>';
		return $parsestr;
	
	}
	
	/**
	 * 代码广告标签
	 * 
	 * @param  Array 	$tag		参数选择
	 * @return String	$parsestr	返回组装后的数据
	 */
	public function _code($tag) {
		$map = "";
		$result = ! empty ( $tag ['result'] ) ? $tag ['result'] : str_replace("_", '', __FUNCTION__);
		$map .= ($tag ['id']) ? " adid='{$tag['id']}' and lang='" . $this->lang . "'" : "'";
		$sql = "M('Ad')->";
		$sql .= "where(\"{$map}\")->";
		$sql .= ($tag ['field']) ? "field('{$tag['field']}')->" : "";
		$sql .= "find()";
		
		//下面拼接输出语句
		$parsestr = '<?php $' . $result . '=$_result=' . $sql . '; ';
		$parsestr .= ' echo stripslashes($' . $result . '[\'normbody\']);  ?>';
		return $parsestr;
	}
	
	/**
	 * Flash广告标签
	 * 
	 * @param  Array 	$tag		参数选择
	 * @param  String   $content	前台内容模板
	 * @return String	$parsestr	返回组装后的数据
	 */
	public function flash($tag, $content){
		$map = "";
		$result = ! empty ( $tag ['result'] ) ? $tag ['result'] : str_replace("_", '', __FUNCTION__) ;
		$map .= ($tag ['id']) ? " adid='{$tag['id']}' and lang='" . $this->lang . "'" : "'";
		$sql = "D('Ad')->";
		$sql .= "where(\"{$map}\")->";
		$sql .= ($tag ['field']) ? "field('{$tag['field']}')->" : "";
		$sql .= "select()";
		//下面拼接输出语句
		$parsestr = '<?php $' . $result . '=$_result=' . $sql . '; ';
		$parsestr .= 'foreach($_result as $key=>$' . $result . '):?>';
		$parsestr .= $content; //解析在article标签中的内容
		$parsestr .= '<?php endforeach;?>';
		return $parsestr;
	}
 

}