<?php
namespace Jzadmin\Model;
class AdminModel extends CommonModel{
	
	protected $_validate=array(
	
	  array('nickname','require','管理员名称不能为空！'), //默认情况下用正则进行验证
	  array('nickname','','管理员名称已经存在！',0,'unique',1), 
	  array('password','require','密码不能为空！'), //默认情况下用正则进行验证
	  
	);
	
	/**
     * 获取所有的用户角色
     * @param string $condition 查询条件
     * +---------------------------------------------------
     * @return ArrayList
     */
    public function getList( $condition ){
    	$list=$this->where($condition)->order("user_id")->select();
    	$role_list=$this->getCateGory();
        foreach ($list as $k => $v) {
            $list[$k]['statusTxt'] = $v['status'] == 1 ? "启用" : "禁用";
            $list[$k]['rolename']=array_key_exists($v['role_id'], $role_list) ? $role_list[$v['role_id']]['name']:"";
        }
        return $list;
    }
    
    /**
     * 获取所有的角色
     * Enter description here ...
     */
    public function getCateGory(){
        $list = M('AdminRole')->field("id,name")->select();          //获取分类结构
        foreach ($list as $key=>$value){
        	$list[$value['id']]=$value;
        }
       return $list; 
    }

}