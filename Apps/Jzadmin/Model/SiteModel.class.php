<?php
namespace Jzadmin\Model;
/**
 * 
 * 站点设置的model类
 * @author Alan
 *
 */
class SiteModel extends CommonModel {
	protected $_validate = array (array ('varname', 'require', '变量名称不能为空', 1 ), array ('info', 'require', '介绍必须', 1 ), array ('groupid', 'require', '参数分类必须', 1 ), array ('input_type', 'require', '变量类型必须', 1 ) );
	
	/**
	 * 
	 * 创建site的数据模型
	 * @param array $postData	过滤提交的参数
	 */
	public function createData($postData) {
		$data = array ();
		$data ['varname'] = isset ( $postData ['varname'] ) ? $postData ['varname'] : "";
		$data ['groupid'] = isset ( $postData ['groupid'] ) ? intval ( $postData ['groupid'] ) : 0;
		$data ['input_type'] = isset ( $postData ['input_type'] ) ? $postData ['input_type'] : "text";
		$data ['info'] = isset ( $postData ['info'] ) ? $postData ['info'] : "";
		$data ['value'] = isset ( $postData ['value'] ) ? $postData ['value'] : "";
		$data ['html_text'] = isset ( $postData ['html_text'] ) ? $postData ['html_text'] : "";
		$data ['mark'] = isset ( $postData ['mark'] ) ? $postData ['mark'] : "";
		$data ['lang'] = $this->lang;
		$data ['ctime'] = time ();
		return $data;
	}
	
	/**
	 * 
	 * 显示文本控件
	 * @param array $inputValue
	 */
	public function createInput($inputValue) {
		if (empty ( $inputValue ))
			return false;
		$input_string = "";
		switch ($inputValue ['input_type']) {
			case "text" :
				$input_string = '<input name="' . $inputValue ['varname'] . '" type="text" class="form-control w30" value="' . $inputValue ['value'] . '" />';
				break;
			case "textarea" :
				$input_string = '<textarea name="' . $inputValue ['varname'] . '" cols="" rows="" class="form-control w30" >' . $inputValue ['value'] . '</textarea>';
				break;
			case "select" :
				$input_value = ! empty ( $inputValue ['html_text'] )&& strpos($inputValue ['html_text'], ",") ? explode ( ",", $inputValue ['html_text'] ) : array ($inputValue ['html_text']);
				$check_value = ! empty ( $inputValue ['value'] )&& strpos($inputValue ['value'], ",") ? explode ( ",", $inputValue ['value'] ) : array ($inputValue ['value']);
				$option_string = "";
				foreach ( $input_value as $value ) {
					$optino_array = explode ( "|", $value );
					$check_string = in_array ( $optino_array [0], $check_value ) ? "selected" : "";
					$option_string .= ' <option value="' . $optino_array [0] . '" ' . $check_string . '>' . $optino_array [1] . '</option>';
				}
				$input_string = '<select id="' . $inputValue ['varname'] . '" name="' . $inputValue ['varname'] . '"  class="form-control w30" >' . $option_string . '</select>';
				break;
			case "radio" :
				$input_value = ! empty ( $inputValue ['html_text'] ) ? explode ( ",", $inputValue ['html_text'] ) : array ();
				$check_value = ! empty ( $inputValue ['value'] ) ? explode ( ",", $inputValue ['value'] ) : array ();
				$option_string = "";
				foreach ( $input_value as $key => $value ) {
					$optino_array = explode ( "|", $value );
					$check_string = in_array ( $optino_array [0], $check_value ) ? "checked" : "";
					$option_string .= '<label style="float:left; margin-right:10px;"><input type="radio" ' . $check_string . ' name="' . $inputValue ['varname'] . '[]" value="' . $optino_array [0] . '" id="' . $inputValue ['varname'] . '_' . $key . '">' . $optino_array [1] . '</label>';
				}
				$input_string = $option_string;
				break;
			case "checkbox" :
				$input_value = ! empty ( $inputValue ['html_text'] ) ? explode ( ",", $inputValue ['html_text'] ) : array ();
				$check_value = ! empty ( $inputValue ['value'] ) ? explode ( ",", $inputValue ['value'] ) : array ();
				$option_string = "";
				foreach ( $input_value as $key => $value ) {
					$optino_array = explode ( "|", $value );
					$check_string = in_array ( $optino_array [0], $check_value ) ? "checked" : "";
					$option_string .= '<label style="float:left; margin-right:10px;"><input type="checkbox" name="' . $inputValue ['varname'] . '[]" ' . $check_string . ' value="' . $optino_array [0] . '" id="' . $inputValue ['varname'] . '_' . $key . '">' . $optino_array [1] . '</label>';
				}
				$input_string = $option_string;
				break;
			case "file" :
				$input_string = '<input name="' . $inputValue ['varname'] . '_txt" id="' . $inputValue ['varname'] . '_txt" type="text" class="form-control w30" value="' . $inputValue ['value'] . '" style=" float:left" /><input name="' . $inputValue ['varname'] . '" type="file" style="margin-top:7px; float:left"><img src="' . $inputValue ['value'] . '" width="100" />';
				break;
			case "multipart" :
				$maultipart_array = ! empty ( $inputValue ['value'] ) ? unserialize ( $inputValue ['value'] ) : array ();
				$option_string = "";
				foreach ( $maultipart_array as $key => $value ) {
					$option_string .= '<tr><td>' . $key . '</td><td><input type="text" name="' . $inputValue ['varname'] . '_' . $key . '" id="' . $inputValue ['varname'] . '_' . $key . '" class="form-control w30" value="' . $value . '"></td> </tr>';
				}
				$input_string = '<input name="' . $inputValue ['varname'] . '" type="hidden" value="multipart"><table class="table table-bordered table-hover definewidth">' . $option_string . '</table>';
				break;
			default :
				$input_string = '<input name="' . $inputValue ['varname'] . '" type="text" class="form-control w30" value="' . $inputValue ['value'] . '" />';
				break;
		}
		
		return $input_string;
	}
	
	/**
	 * 
	 * 更改系统的配置文件
	 */
	public function updateConfig() {
		$result = $this->field ( "varname,input_type,value,info,lang,groupid" )->select ();
		if ($result) {
			$user_config =$sys_config= array ();
			foreach ( $result as $value ) {
				if ($value ['input_type'] == 'multipart') {
					$user_config [$value ['lang']] [$value ['varname']] = unserialize ( $value ['value'] );
				} else {
					$user_config [$value ['lang']] [$value ['varname']] = $value ['value'];
				}
				if ($value['groupid']==3&&$value['lang']==$this->lang){ //当是系统参数
					if ($value ['input_type'] == 'multipart') {
						$sys_config  [$value ['varname']] = unserialize ( $value ['value'] );
					} else {
						$sys_config  [$value ['varname']] = $value ['value'];
					}
				}
			}
			unset ( $result );
			foreach ( $user_config as $key => $value ) {
				F ( "Config_" . $key, $value, INCLUDE_PATH );
			}
			F("SysConfig",$sys_config,INCLUDE_PATH);
		}
		F ( "Lang", null,INCLUDE_PATH );
		$this->clearCache(); //清除系统缓存
	}
	/**
	 * +-----------------------------------------------------
	 * 获取系统配置的值
	 * +-----------------------------------------------------
	 * @param String	 $key		配置的KEY值
	 */
	public function getConfirByKey($key) {
		if (empty ( $key ))
			return false;
		$info=$this->where("varname='".$key."' and lang='".$this->lang."'")->limit(1)->find();
		return $info ? $info['value'] : "";
	}

	/**
	 * +-----------------------------------------------------
	 * 清除缓存
	 * +-----------------------------------------------------
	 * @param String	 $key		配置的KEY值
	 */
	public function clearCache(){
		$caches = array (
			"HomeCache" => array ("name" => "网站前台模板缓存文件", "path" => RUNTIME_PATH . "Cache/Home/" ), 
			"AdminCache" => array ("name" => "网站后台模板缓存文件", "path" => RUNTIME_PATH . "Cache/".ADMIN_NAME."/" ), 
			"HomeData" => array ("name" => "网站数据库字段缓存文件", "path" => RUNTIME_PATH . "Data/" ), 
			"HomeLog" => array ("name" => "网站前台日志缓存文件", "path" => RUNTIME_PATH . "Logs/Home/" ),
			"AdminLog" => array ("name" => "网站后台日志缓存文件", "path" => RUNTIME_PATH . "Logs/".ADMIN_NAME."/" ), 
			"HomeTemp" => array ("name" => "网站临时数据缓存文件", "path" => RUNTIME_PATH . "Temp/" ), 
			"Homeruntime" => array ("name" => "网站配置缓存文件", "path" => RUNTIME_PATH . "common~runtime.php" ) 
		);
		
		foreach ( $caches as $key=>$value ) {
				delDirAndFile ( $value['path'] );
		}
		F ( "All_Category_" . $this->lang, null );
		F ( "Category_" . $this->lang, null );
		
	}
	
}