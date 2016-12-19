<?php
namespace Home\Model;
class MenuModel extends CommonModel {
	
	protected $_field = "id,parent_id,title,model_name,param,position,thumb,status,site_title,site_keyword,site_description,pagesize,sort,typeid,route,template_list,template_show,listtype,typeid,en_title";
	
	/**
	 * +--------------------------------------------------------------
	 * 获取分类对象
	 *+--------------------------------------------------------------
	 *
	 * @param 	Array 	$condition
	 */
	public function getCategoryObj() {
		$field_values = explode ( ",", $this->_field );
		array_splice ( $field_values, 3, 0, 'fullname' );
		$category = new \Org\Util\Category ( 'Menu', $field_values );
		return $category;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 获取菜单的列表
	 * +--------------------------------------------------------------
	 */
	public function getCategory($condition = array()) {
		$temp = F ( "All_Category_" . $this->lang );
		if (! $temp) {
			$category = $this->getCategoryObj ();
			$temp = $category->getList ( $condition, 0, "sort desc,id desc" ); //获取分类结构
			F ( "All_Category_" . $this->lang, $temp );
		}
		return $temp;
	}
	
	
	
	/**
	 * +--------------------------------------------------------------
	 * 获取所有的栏目内容
	 * +--------------------------------------------------------------
	 */
	public function getAllMenu() {
		
		$row = F ( "Category_" . $this->lang );
		if (! $row) {
			$category = $this->getCategory ( array ("lang" => $this->lang ) );
			$row = array ();
			if ($category) {
				foreach ( $category as $key => $value ) {
						$value ['arcchild_list'] = $this->getChildByParentId ( $value ['id'], $category );
						if ($value ['arcchild_list']) {
							$childId = getSubByKey ( $value ['arcchild_list'], "id" );
							$childId [] = $value ['id'];
							$value ['arcchild'] = implode ( ",", $childId );
						}
						$value ['parent_list'] =$this->getParentByCatId ( $value ['id'], $category );
						$value ['url'] = createHomeUrl ( $value );
						$row [$value ['id']] = $value;
				}
				
				F ( "Category_" . $this->lang, $row );
			}
		}
		
		return $row;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 根据栏目ID获取栏目的信息
	 * +--------------------------------------------------------------
	 * 
	 * @param  int 		$id		栏目ID
	 * @return Array    $row	栏目的信息
	 */
	public function getMenuById($id) {
		if (empty ( $id ))
			return false;
		$category = $this->getAllMenu ();
		$row = array ();
		$ids=!is_array($id) ? explode(",", $id) : array($id);
		foreach ($ids as $key=>$value){  
			if (array_key_exists($value, $category)){
				$row [$value] = $category[$value];
			}
		}
		unset($category);
		return $row;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 根据父ID获取所有的子类
	 * +--------------------------------------------------------------
	 * 
	 * @param	 int   	$parentId	父类ID
	 * @param	 Array 	$category	查找的数据
	 * @return	 Array 	$childMenu 	返回子类信息
	 */
	public function getChildByParentId($parentId, $category = array()) {
		if (empty ( $parentId ))
			return false;
		if (empty ( $category )) {
			$category = $this->getCategory ();
		}
		$categoryModel = $this->getCategoryObj ();
		$childMenu = $categoryModel->getTree ( $category, $parentId );
		$row = array ();
		if ($childMenu) {
			foreach ( $childMenu as $key => $value ) {
				if($value['status']==1){
					$value ['url'] = createHomeUrl ( $value );
					$row [$value ['id']] = $value;
				}
			}
		}
		unset ( $childMenu );
		return $row;
	}
	
	/**
	 * +--------------------------------------------------------------
	 * 根据当前分类ID获取父类路径
	 * +--------------------------------------------------------------
	 * 
	 * @param int 	$catId
	 * @return ArrayIterator 父类路径
	 */
	public function getParentByCatId($catId) {
		if (empty ( $catId ))
			return false;
		$categoryModel = $this->getCategoryObj ();
		$path = $categoryModel->getPath ( $catId );
		$row = array ();
		if ($path) {
			foreach ( $path as $key => $value ) {
				if($value['status']==1){
					$value ['url'] = createHomeUrl ( $value );
					$row [$value ['id']] = $value;
				}
			}
		}
		unset ( $path );
		return $row;
	}

}