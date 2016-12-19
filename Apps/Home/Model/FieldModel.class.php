<?php
namespace  Home\Model;
class FieldModel extends  CommonModel {
	 
	/**
	 * 根据模型ID获取所有字段
	 * 
	 * @param int $moduleId	模型ID
	 */
	public function getFieldByModuelId($moduleId){
		$field_model=D(ADMIN_NAME."/Field");
		$field_list=$field_model->getModuleField($moduleId);
		$field_row=array();
		foreach ($field_list as $key=>$value){
			if ($value['status']==0&&$value['ispost']==0){
				$field_row[]=$value;
			}
		}
		return $field_row;
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
		return D(ADMIN_NAME."/Field")->checkfield($fields, $postData);
	}
	
	/**
	 * 
	 * 格式化后端表达验证
	 * +-----------------------------------------------------
	 * @param 	Array $fieldList			模型字段列表
	 * @return  Array $row				模型字段的自动验证
	 */
	public function validate($fieldList){
		return D(ADMIN_NAME."/Field")->validate($fieldList);
	}
}
?>