<?php
namespace Jzadmin\Model;
/**
 * +-------------------------------------------
 * 字段模型类
 * @author Alan
 * +-------------------------------------------
 */
class FieldModel extends CommonModel {
	
	/**
	 * +---------------------------------------
	 * 获取所有的字段类型
	 * +---------------------------------------
	 */
	public function FieldModel($keys = '') {
		$field_array = array ();
		$field_array ['catid'] = '栏目ID';
		$field_array ['title'] = '标题';
		//$field_array ['typeid'] = '类别';
		$field_array ['text'] = '单行文本';
		$field_array ['textarea'] = '多行文本';
		$field_array ['editor'] = '编辑器';
		$field_array ['select'] = '下拉列表';
		$field_array ['radio'] = '单选按钮';
		$field_array ['checkbox'] = '复选框';
		$field_array ['image'] = '单张图片';
		$field_array ['images'] = '多张图片';
		$field_array ['file'] = '单文件上传';
		$field_array ['files'] = '多文件上传';
		$field_array ['number'] = '数字';
		$field_array ['datetime'] = '日期和时间';
		$field_array ['posid'] = '推荐位';
		//$field_array ['linkage'] = '联动菜单';
		$field_array ['template'] = '模板选择';
		$field_array ['verify'] = '验证码';
		return empty ( $keys ) ? $field_array : $field_array [$keys];
	}
	
	/**
	 * +---------------------------------------
	 * 获取默认字段
	 * +---------------------------------------
	 */
	public function defaultField() {
		//字段表
		$field = array (
		'catid' => array ('name' => "栏目", "type" => 'catid', "default_value" => "smallint(5) unsigned NOT NULL DEFAULT '0'", "required" => 1 ), 
		'title' => array ('name' => "标题", "type" => 'title', "default_value" => "varchar(255) NOT NULL DEFAULT ''", "required" => 1 ), 
		'thumb' => array ('name' => "缩略图", "type" => 'image', "default_value" => "varchar(255) NOT NULL DEFAULT ''" ,"setup"=>"a:6:{s:4:&quot;size&quot;;s:0:&quot;&quot;;s:7:&quot;default&quot;;s:0:&quot;&quot;;s:14:&quot;upload_maxsize&quot;;s:0:&quot;&quot;;s:15:&quot;upload_allowext&quot;;s:16:&quot;jpg,jpeg,gif,png&quot;;s:9:&quot;watermark&quot;;s:1:&quot;0&quot;;s:4:&quot;more&quot;;s:1:&quot;0&quot;;}"), 
		'keyword' => array ('name' => "关键词", "type" => 'text', "default_value" => "varchar(120) NOT NULL DEFAULT ''" ), 
		'description' => array ('name' => "描述", "type" => 'textarea', "default_value" => "varchar(255) NOT NULL DEFAULT ''" ), 
		'content' => array ('name' => "内容", "type" => 'editor', "default_value" => "longtext NOT NULL" ), 
		'posid' => array ('name' => "推荐位", "type" => 'posid', "default_value" => "varchar(10)  NOT NULL DEFAULT ''" ), 
		'status' => array ('name' => "状态", "type" => 'radio', "default_value" => "tinyint(1) unsigned NOT NULL DEFAULT '0'", 
		"setup" => "a:5:{s:7:&quot;options&quot;;s:12:&quot;是|0
否|1&quot;;s:9:&quot;fieldtype&quot;;s:7:&quot;varchar&quot;;s:10:&quot;numbertype&quot;;s:1:&quot;1&quot;;s:10:&quot;labelwidth&quot;;s:0:&quot;&quot;;s:7:&quot;default&quot;;s:0:&quot;&quot;;}" ), 'listorder' => array ('name' => "排序", "type" => 'number', "default_value" => "int(10) unsigned NOT NULL DEFAULT '0'" ), 'hits' => array ('name' => "浏览量", "type" => 'number', "default_value" => "int(10) unsigned NOT NULL DEFAULT '0'" ), 'createtime' => array ('name' => "发布时间", "type" => 'datetime', "default_value" => "int(11) unsigned NOT NULL DEFAULT '0'" ) );
		return $field;
	}
	
	/**
	 * 
	 * 获取修改的字段
	 * @param Array $info	字段参数
	 * @param string $do	操作方法 add/edit
	 */
	public function get_tablesql($info, $do) {
		
		$fieldtype = $info ['type'];
		if ($info ['setup'] ['fieldtype']) {
			$fieldtype = $info ['setup'] ['fieldtype'];
		}
		$moduleid = $info ['moduleid'];
		$default = $info ['setup'] ['default'];
		$field = $info ['field'];
		$module_model = D ( "Module" );
		$module_info = $module_model->getModuleIdByModuleId ( $moduleid );
		$tablename = C ( 'DB_PREFIX' ) . strtolower ( $module_info ['name'] );
		$maxlength = intval ( $info ['maxlength'] );
		$minlength = intval ( $info ['minlength'] );
		$numbertype = $info ['setup'] ['numbertype'];
		$oldfield = $info ['oldfield'];
		if ($do == 'add') {
			$do = ' ADD ';
		} else {
			$do = " CHANGE `$oldfield` ";
		}
		
		switch ($fieldtype) {
			case 'varchar' :
				if (! $maxlength)
					$maxlength = 255;
				$maxlength = min ( $maxlength, 255 );
				$sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( $maxlength ) NOT NULL DEFAULT '$default'";
				break;
			
			case 'title' :
				if (! $maxlength)
					$maxlength = 255;
				$maxlength = min ( $maxlength, 255 );
				$sql [] = "ALTER TABLE `$tablename` $do `title` VARCHAR( $maxlength ) NOT NULL DEFAULT '$default'";
				break;
			
			case 'catid' :
				$sql = "ALTER TABLE `$tablename` $do `$field` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0'";
				break;
			
			case 'number' :
				$decimaldigits = $info ['setup'] ['decimaldigits'];
				$default = $decimaldigits == 0 ? intval ( $default ) : floatval ( $default );
				$sql = "ALTER TABLE `$tablename` $do `$field` " . ($decimaldigits == 0 ? 'INT' : 'decimal( 10,' . $decimaldigits . ' )') . " " . ($numbertype == 1 ? 'UNSIGNED' : '') . "  NOT NULL DEFAULT '$default'";
				break;
			
			case 'tinyint' :
				if (! $maxlength)
					$maxlength = 3;
				$maxlength = min ( $maxlength, 3 );
				$default = intval ( $default );
				$sql = "ALTER TABLE `$tablename` $do `$field` TINYINT( $maxlength ) " . ($numbertype == 1 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '$default'";
				break;
			
			case 'smallint' :
				$default = intval ( $default );
				if (! $maxlength)
					$maxlength = 8;
				$maxlength = min ( $maxlength, 8 );
				$sql = "ALTER TABLE `$tablename` $do `$field` SMALLINT( $maxlength ) " . ($numbertype == 1 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '$default'";
				break;
			
			case 'int' :
				$default = intval ( $default );
				$sql = "ALTER TABLE `$tablename` $do `$field` INT " . ($numbertype == 1 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '$default'";
				break;
			
			case 'mediumint' :
				$default = intval ( $default );
				$sql = "ALTER TABLE `$tablename` $do `$field` INT " . ($numbertype == 1 ? 'UNSIGNED' : '') . " NOT NULL DEFAULT '$default'";
				break;
			
			case 'mediumtext' :
				$sql = "ALTER TABLE `$tablename` $do `$field` MEDIUMTEXT NOT NULL";
				break;
			
			case 'text' :
				$sql = "ALTER TABLE `$tablename` $do `$field` TEXT NOT NULL";
				break;
			
			case 'posid' :
				$sql = "ALTER TABLE `$tablename` $do `$field` TINYINT(2) UNSIGNED NOT NULL DEFAULT '0'";
				break;
			
			//case 'typeid':
			//$sql = "ALTER TABLE `$tablename` $do `$field` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0'";
			//break;			
			

			case 'datetime' :
				$sql = "ALTER TABLE `$tablename` $do `$field` INT(11) UNSIGNED NOT NULL DEFAULT '0'";
				break;
			
			case 'editor' :
				$sql = "ALTER TABLE `$tablename` $do `$field` TEXT NOT NULL";
				break;
			
			case 'image' :
				$sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( 80 ) NOT NULL DEFAULT ''";
				break;
			
			case 'images' :
				$sql = "ALTER TABLE `$tablename` $do `$field` MEDIUMTEXT NOT NULL";
				break;
			
			case 'file' :
				$sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( 80 ) NOT NULL DEFAULT ''";
				break;
			
			case 'files' :
				$sql = "ALTER TABLE `$tablename` $do `$field` MEDIUMTEXT NOT NULL";
				break;
			case 'template' :
				if (! $maxlength)
					$maxlength = 255;
				$maxlength = min ( $maxlength, 255 );
				$sql [] = "ALTER TABLE `$tablename` $do `$field` VARCHAR( $maxlength ) NOT NULL DEFAULT ''";
				break;
		}
		return $sql;
	}
	
	/**
	 * +---------------------------------------
	 * 效验模型提交上来的字段数据
	 * +---------------------------------------
	 * @param Array $fields		模型字段里的字段
	 * @param Array $postData	POST提交的字段数据
	 * 
	 * @return Array 			返回过滤后的数组
	 */
	public function checkfield($fields, $postData) {
		foreach ( $postData as $key => $val ) {
			$setup = $fields [$key] ['setup'];			
			if ($setup ['multiple'] || $fields [$key] ['type'] == 'checkbox') {
				$postData [$key] = implode ( ',', $postData [$key] );
			} elseif ($fields [$key] ['type'] == 'datetime') {
				$postData [$key] = strtotime ( $postData [$key] );
			} elseif ($fields [$key] ['type'] == 'textarea') {
				$postData [$key] = addslashes ( $postData [$key] );
			} elseif ($fields [$key] ['type'] == 'image' || $fields [$key] ['type'] == 'file') {
				$postData [$key] = addslashes ( $postData [$key] ); 
			} elseif ($fields [$key] ['type'] == 'images' || $fields [$key] ['type'] == 'files') {
				$arrdata = array ();
				foreach ( $postData [$key] as $k => $res ) {
					if (! empty ( $postData [$key] [$k] ))
						$arrdata [] = $postData [$key] [$k]['name'] . '|' . $postData [$key] [$k]['sort'];
				}
				$postData [$key] = implode ( ':::', $arrdata );
				
			} elseif ($fields [$key] ['type'] == 'editor') {
				//自动提取摘要
				if ($postData ['description'] == '' && isset ( $postData ['content'] )) {
					$content = stripslashes ( $postData ['content'] );
					$description_length = intval ( $postData ['description_length'] );
					$postData ['description'] = msubstr ( str_replace ( array ("\r\n", "\t", '[page]', '[/page]', '&ldquo;', '&rdquo;' ), '', strip_tags ( $content ) ),0, $description_length );
					$postData ['description'] = addSlashesFun ( $postData ['description'] );
				}
				//自动提取缩略图
				if ($postData ['thumb'] == '' && isset ( $postData ['content'] )) {
					$content = $content ? $content : stripslashes ( $postData ['content'] );
					$auto_thumb_no = intval ( $postData ['auto_thumb_no'] ) * 3;
					if (preg_match_all ( "/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $content, $matches )) {
						$postData ['thumb'] = $matches [$auto_thumb_no] [0];
					}
				}
				$postData ['content'] = addSlashesFun( $postData ['content']);
			}
		}
		 
		return $postData;
	}
	
	/**
	 * 
	 * 获取模型的字段
	 * +---------------------------------------------------
	 * @param $moduleid 	模型ID
	 * @return Array 		返回获取后的字段数组
	 */
	public function getModuleField($moduleid,$status=0) {
		$list = F ( $moduleid . "_Field" );
		if (! $list) {
			$list = $this->updateCacheField ( $moduleid );
		}
		$field_array=array();
		foreach ($list as $key=>$value){
			if ($value['status']==$status){
				$value['setup']=serialize2array($value['setup']);
				$field_array[$key]=$value;
			}
		}
		unset($list);
		return $field_array;
	}
	
	/**
	 * 更新字段缓存
	 * +---------------------------------------------------
	 * @param $moduleid 	模型ID
	 * @return Array 		返回字段数组
	 * 
	 */
	public function updateCacheField($moduleid) {
		$list = $this->order ( 'listorder asc,id asc' )->where ( 'moduleid=' . $moduleid )->select ();
		if ($list) {
			$pkid = 'field';
			$data = array ();
			foreach ( $list as $key => $val ) {
				$data [$val [$pkid]] = $val;
			}
			F ( $moduleid . "_Field", $data );
			unset($list);
			return $data;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * 格式化后端表达验证
	 * +-----------------------------------------------------
	 * @param 	Array $fieldList			模型字段列表
	 * @return  Array $row				模型字段的自动验证
	 */
	public function validate($fieldList){
		if (empty ( $fieldList ))
			return false;
		$validate_data = array ();
		foreach ( $fieldList as $key => $value ) {
			if ($value ['required']) {
				$validate_data [] = array ($value ['field'], 'require', $value ['name'] . '必须填!',1 );
			}
			if (!empty($value['validate'])){
				$value['validate']=str_replace("field", $value ['field'], $value['validate']);
				$validate=stripcslashes($value['validate']);
       		 	eval("\$r = $validate;");
				$validate_data=array_merge($validate_data,$r);
			}
		}
		return $validate_data;
			
	}
}