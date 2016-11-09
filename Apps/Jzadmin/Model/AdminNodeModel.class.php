<?php
namespace Jzadmin\Model;
use \Org\Util\Category;
class AdminNodeModel extends CommonModel {
	
	/**
      +----------------------------------------------------------
	 * 显示一级菜单
      +----------------------------------------------------------
	 */
	public function show_menu() {
		$M = M ( "AdminNode" );
		$node_id = $this->checkLogin ();
		if (empty ( $node_id )) {
			$sql = "level=1 and status=1";
		} else {
			$sql = "level=1 and status=1 and id in( {$node_id} )";
		}
		$list = $M->where ( $sql )->field ( "id,name,title" )->order ( "sort asc" )->select ();
		return $list;
	}
	
	/**
      +----------------------------------------------------------
	 * 显示二级菜单
      +----------------------------------------------------------
	 */
	public function show_sub_menu() {
		$M = M ( "AdminNode" );
		$node_id = $this->checkLogin ();
		if (empty ( $node_id )) {
			$sql_1 = "level=4 and status=1";
			$sql_2 = "level=2 and status=1";
		} else {
			$sql_1 = "level=4 and status=1 and id in( {$node_id} )";
			$sql_2 = "level=2 and status=1 and id in( {$node_id} )";
		}
		$navlist = $M->where ( $sql_1 )->field ( "id,name,title,pid,module" )->order ( "sort asc" )->select ();
		$nav_array = array ();
		foreach ( $navlist as $key => $value ) {
			$nav_array [$value ['pid']] [] = $value;
		}
		unset ( $navlist );
		$list = $M->where ( $sql_2 )->field ( "id,name,title,pid,module" )->order ( "sort asc" )->select ();
		$info = array ();
		foreach ( $list as $key => $value ) {
			$info [$value ['pid']] [] = $value;
		}
		unset ( $list );
		$show_menu = $this->show_menu ();
		$row = array ();
		foreach ( $show_menu as $key => $value ) {
			$row [$key] ["id"] = $value ['id'];
			if (array_key_exists ( $value ['id'], $nav_array )) {
				foreach ( $nav_array [$value ['id']] as $key_nav => $v_nav ) {
					$nodeArray = array_key_exists ( $v_nav ['id'], $info ) ? $info [$v_nav ['id']] : array ();
					if (!isset($row [$key] ["homePage"])){
						$row [$key] ["homePage"] = empty ( $nodeArray ) ? 0 : $nodeArray [0] ['id'];
					}
					foreach ( $nodeArray as $k => $v ) {
						$jump=createAdminUrl($v);
						$nodeArray [$k] = array ("id" => $v ['id'], "text" => $v ['title'], "href" => $jump );
					}
					$row [$key] ["menu"] [] = array ("text" => $v_nav ['title'], "items" => $nodeArray );
				}
			}
		
		}
		return $row;
	}
	
	/**
	 * 获取登录的权限
	 * Enter description here ...
	 */
	public function checkLogin() {
		if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
			redirect ( U ( "Login/index" ) ); //跳转到认证网关
		}
		
		$role_id = $_SESSION ['role_id'];
		if ($role_id == C ( 'ADMIN_ROLE_ID' )) {
			return "";
		} else {
			$adminAccess = M ( "AdminAccess" );
			$result = $adminAccess->where ( "role_id='{$role_id}'" )->field ( "node_id" )->select ();
			$node_str = "";
			if ($result) {
				foreach ( $result as $key => $value ) {
					$node_str .= $value ['node_id'] . ",";
				}
				$node_str = rtrim ( $node_str, "," );
				return $node_str;
			} else {
				unset ( $_SESSION [C ( 'USER_AUTH_KEY' )] );
				unset ( $_SESSION );
				session_destroy ();
				$this->error ( "无权访问", U ( "Login/index" ) );
			}
		
		}
	}
	
	/**
	 * 获取当前位置的面包屑
	 * Enter description here ...
	 */
	public function getPosition() {
		$controller_name = CONTROLLER_NAME; // 获取当前模块的名称CONTROLLER_NAME."====".ACTION_NAME;
		$action_name=ACTION_NAME;
		$data=array();
		$moduleid=isset($_GET['moduleid']) ? intval($_GET['moduleid']) : "";  
		$moduleid_sql=!empty($moduleid) ? " and module like '%moduleid/".$moduleid."%' " : "";
		$parentNmae= $this->where ( "name='{$controller_name}' and level=2 ".$moduleid_sql )->field ( "id,title,pid,module,name" )->select (); //获取父类
		if ($parentNmae) {
			$pid_str = implode(",", getSubByKey($parentNmae,"id"));
			$childName = $this->where ( "name='" . $action_name . "' and  level=3 and pid in ({$pid_str})" )->field ( "id,title,pid,module,name" )->find ();
			if (!empty($moduleid)){  
				$childName ['module']  = ! empty ( $childName ['module'] ) ? $childName['name']."/".$childName ['module'] : $action_name;
				$childName ['module']  = $childName ['module']."/moduleid/".$moduleid;
			}else {
				$childName ['module']  = ! empty ( $childName ['module'] ) ? $childName['name']."/".$childName ['module'] : $action_name;
			}
			$childName ['name'] =$parentNmae[0]['name'];
			$data [] = array ("title" => $childName ['title'], "url" =>createAdminUrl($childName) );
		 
			$parent_pid = $childName ['pid']; //获取子类的父ID
			foreach ($parentNmae as $value){
				if ($value['id']==$parent_pid){
					$data [] = array ("title" => $value ['title'], "url" => createAdminUrl($value) );
					break;
				}
			}
			krsort ( $data );
			return $data;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取节点名称
	 * Enter description here ...
	 * @param unknown_type $info
	 */
    public function getPid($info) {
        $arr = array("0"=>"请选择", "1"=>"项目","4"=>"栏目菜单", "2"=>"模块控制器", "3"=>"操作");
        foreach ($arr as $key=>$value) {
        	if($key!=0){
	            $selected = $info['level'] == $key ? " selected='selected'" : "";
	            $info['levelOption'].='<option value="' . $key . '" ' . $selected . '>' . $arr[$key] . '</option>';
        	}
        }
        $level = $info['level'] - 1;
        $cat = new Category('AdminNode', array('id', 'pid', 'title', 'fullname'));
        $list = $cat->getList();               //获取分类结构
        $option = $level == 0 ? '<option value="0" level="-1">根节点</option>' : '<option value="0" disabled="disabled">根节点</option>';
        foreach ($list as $k => $v) {
            $disabled = $v['level'] == $level ? "" : '';// disabled="disabled"
            $selected = $v['id'] != $info['pid'] ? "" : ' selected="selected"';
            $option.='<option value="' . $v['id'] . '"' . $disabled . $selected . '  level="' . $v['level'] . '">' . $v['fullname'] . '</option>';
        }
        $info['pidOption'] = $option;
        return $info;
    }
    
    /**
     * 保存节点信息
     * Enter description here ...
     */
 	public function addNode() {
        return $this->add($_POST) ? array('status' => 1, info => '添加节点信息成功', 'url' => U('Node/index')) : array('status' => 0, info => '添加节点信息失败');
    }
    
    /**
     * 修改节点信息
     * Enter description here ...
     */
	 public function editNode() {
        return $this->save($_POST) ? array('status' => 1, info => '更新节点信息成功', 'url' => U('Node/index')) : array('status' => 0, info => '更新节点信息失败');
    }
    
  /**
   * 获取节点列表
   * Enter description here ...
   */  
  public function nodeList() {

        $cat = new Category('AdminNode', array('id', 'pid', 'title', 'fullname','status','level','sort','name'));
        $temp = $cat->getList();               //获取分类结构
        $level = array("1" => "项目（GROUP_NAME）", "2" => "模块控制器(MODEL_NAME)", "3" => "操作（ACTION_NAME）","4"=>"栏目菜单");
        foreach ($temp as $k => $v) {
            $temp[$k]['statusTxt'] = $v['status'] == 1 ? "启用" : "禁用";
            $temp[$k]['chStatusTxt'] = $v['status'] == 0 ? "启用" : "禁用";
            $temp[$k]['level'] = $level[$v['level']];
            $list[$v['id']] = $temp[$k];
        }
        unset($temp);
        return $list;
    }
	
}