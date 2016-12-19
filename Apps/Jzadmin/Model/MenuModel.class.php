<?php
namespace Jzadmin\Model;
/**
 * 
 * 菜单操作的模型类
 * @author Alan
 * 
 */
class MenuModel extends CommonModel {
	
	protected $_field = "id,parent_id,title,model_name,param,position,thumb,status,site_title,site_keyword,site_description,sort,typeid,route,template_list,template_show,listtype,typeid,pagesize,en_title";
	protected $_validate = array (array ('title', 'require', '栏目名称必须', 1 ) );
	
	/**
	 * 更新菜单缓存
	 */
	public function updateCache() {
		F ( "All_Category_" . $this->lang, null );
		F ( "Category_" . $this->lang, null );
		F ( 'Cat_' . $this->lang, null, INCLUDE_PATH );
		$field_values = explode ( ",", $this->_field );
		array_splice ( $field_values, 3, 0, 'fullname' );
		$category = new \Org\Util\Category ( 'Menu', $field_values );
		$config = $category->getList ( array ("lang" => $this->lang ), 0, "sort desc,id desc" ); //获取分类结构
		$cat = array ();
		foreach ( $config as $value ) {
			if (! empty ( $value ['route'] )) {
				$cat [$value ['route']] = $value ['id'];
			}
		}
		//保存配置
		F ( "All_Category_" . $this->lang, $config );
		F ( 'Cat_' . $this->lang, $cat, INCLUDE_PATH );
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 获取菜单的列表
	 * +--------------------------------------------------------------
	 */
	public function getCategory($condition = array()) {
		$temp = F ( "All_Category_" . $this->lang );
		if (! $temp) {
			$field_values = explode ( ",", $this->_field );
			array_splice ( $field_values, 3, 0, 'fullname' );
			$category = new \Org\Util\Category ( 'Menu', $field_values );
			$temp = $category->getList ( $condition, 0, "sort desc,id desc" ); //获取分类结构
			F ( "All_Category_" . $this->lang, $temp );
		}
		return $temp;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 根据模型的id获取所有的分类
	 * +--------------------------------------------------------------
	 * 
	 * @param int $moduelId
	 * @param int $fid
	 */
	public function getMenuByModuleId($moduelId, $fid = 0) {
		$menu_data = $this->getCategory ( array ("lang" => $this->lang ) );
		$row = $category = array ();
		foreach ( $menu_data as $value ) {
			$category [$value ['id']] = $value;
		}
		foreach ( $menu_data as $key => $value ) {
			$select = ! empty ( $fid ) && $value ['id'] == $fid ? "selected" : "";
			if ($value ['typeid'] == $moduelId ) {
				if ($value ['parent_id'] != 0 && ! array_key_exists ( $value ['parent_id'], $row )) {
					$parent_category = $category [$value ['parent_id']];
					$value ['option'] = ' <option value="' . $parent_category ['id'] . '"  disabled="disabled" >' . $parent_category ['fullname'] . '</option>';
					$row [$parent_category ['id']] = $value;
				}
				$value ['option'] = '<option value="' . $value ['id'] . '" ' . $select . ' >' . $value ['fullname'] . '</option>';
				$row [$value ['id']] = $value;
			}
		}
		return $row;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 根据模型的ID获取模型信息
	 * +--------------------------------------------------------------
	 * 
	 * @param unknown_type $controllerName
	 */
	public function getMenuByTypeid($module_typeid) {
		if (empty ( $module_typeid ))
			return false;
		$module_model = D ( "Module" );
		$module_info = $module_model->getModuleIdByModuleId ( $module_typeid );
		return $module_info;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 根据模型ID获取所有栏目
	 * +--------------------------------------------------------------
	 * 
	 * @param int $moduleId 
	 * @param int $catid 
	 * @return Array $category		返回所有分类
	 */
	public function getMenuByCatId($moduleId, $catid = 0) {
		$module_info = $this->getMenuByTypeid ( $moduleId );
		if (! $module_info) {
			$this->error = "模型不存在";
			return false;
		}
		$category = $this->getMenuByModuleId ( $module_info ['id'], $catid );
		return $category;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 获取该模型中的推荐位
	 * +--------------------------------------------------------------
	 * 
	 *  * @param int $moduleId 
	 */
	public function getMenuFlag($moduleId) {
		$posid_model = D ( "Posid" );
		$flag_list = F ( "RECOMEND_LIST" );
		if (! $flag_list) {
			$flag_list = $posid_model->where ( "catId='" . $moduleId . "'" )->order ( "listorder desc,id desc" )->select ();
		}
		$row = array ();
		foreach ( $flag_list as $value ) {
			if ($value ['catid'] == $moduleId) {
				$row [] = $value;
			}
		}
		return $row;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 获取栏目信息
	 * +--------------------------------------------------------------
	 * 
	 *  * @param int $catid 
	 */
	public function getMenuById($catid){
		$menu_data = $this->getCategory ( array ("lang" => $this->lang ) );
		if ($menu_data){
			foreach ($menu_data as $key=>$value){
				if ($value['id']==$catid){
					return $value;
					break;
				}
			}
			return $this->where("id='".$catid."'")->find();
		}else{
			return $this->where("id='".$catid."'")->find();
		}
	}
}