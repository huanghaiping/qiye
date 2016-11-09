<?php
namespace Jzadmin\Model;
use \Org\Util\Category;
class AdminRoleModel extends CommonModel{

	protected $_validate=array(
	
	  array('name','require','角色名称不能为空！'), //默认情况下用正则进行验证
	  
	);
	
	/**
	 * 获取所有的角色
	 * Enter description here ...
	 * @param array $info 参数数组
	 * @return Array
	 */
	 public function getRole($info = array()) {
        $cat = new Category('AdminRole', array('id', 'pid', 'name'));
        $list = $cat->getList();          //获取分类结构
        foreach ($list as $k => $v) {
            $disabled = $v['id'] == $info['id'] ? ' disabled="disabled"' : "";
            $selected = $v['id'] == $info['pid'] ? ' selected="selected"' : "";
            $info['pidOption'].='<option value="' . $v['id'] . '"' . $selected . $disabled . '>' . $v['fullname'] . '</option>';
        } 
        return $info;
    }
    
 	/**
      +----------------------------------------------------------
     * 添加管理员角色
      +----------------------------------------------------------
     */
    public function addRole() {
        if ($this->add($_POST)) {
            return array('status' => 1, 'info' => "成功添加", 'url' => U("AdminRole/index"));
        } else {
            return array('status' => 0, 'info' => "添加失败，请重试");
        }
    }
    
    /**
      +----------------------------------------------------------
     * 修改管理员角色
      +----------------------------------------------------------
     */
    public function editRole() {
        if ($this->save($_POST)) {
            return array('status' => 1, 'info' => "成功更新", 'url' => U("AdminRole/index"));
        } else {
            return array('status' => 0, 'info' => "更新失败，请重试");
        }
    }
    
    
    /**
     * 获取所有的用户角色
     * @param string $condition 查询条件
     * +---------------------------------------------------
     * @return ArrayList
     */
    public function getList( $condition ){
    	$list=$this->where($condition)->order("id")->select();
        foreach ($list as $k => $v) {
            $list[$k]['statusTxt'] = $v['status'] == 1 ? "启用" : "禁用";
            $list[$k]['statusTip'] = $v['status'] == 1 ? "禁用" : "启用";
        }
        return $list;
    }
    
    /**
     * 更改角色的状态
     *+--------------------------------------------------------------------
     * @param int $id 角色id
     * @param int $status 角色的状态
     */
    public function updateStatus( $id,$status ){
    	$status=$status==0 ? 1 : 0 ;
    	return $this->where("id='{$id}'")->setField('status',$status);
    }

}