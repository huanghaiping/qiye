<?php
namespace Jzadmin\Controller;
class NodeController extends CommonController {
	
	/**
	 * 获取节点列表
	 * Enter description here ...
	 */
	public function index(){
		
		$this->assign("list", D ( "AdminNode" )->nodeList());
        $this->display();
	}
	/**
	 * 添加节点
	 * Enter description here ...
	 */
	public function add() {
		$this->assign ( "info", D ( "AdminNode" )->getPid ( array ('level' => 1 ) ) );
		$this->display ();
	}
	
	/**
	 * 保存节点信息
	 * Enter description here ...
	 */
	public function saveAdd() {
		$adminNole = D ( "AdminNode" );
		$id = isset ( $_POST ["id"] ) ? intval ( $_POST ["id"] ) : "";
		if (empty($_POST['name'])){
			$this->error("请输入节点名称");
		}
		if (empty ( $id )) {
			$result = $adminNole->addNode ();
		} else {
			$result = $adminNole->editNode ();
		}
		if ($result ['status']) {
			$this->success ( $result ["info"], $result ["url"] );
		} else {
			$this->error ( $result ["info"], $result ["url"] );
		}
	}
	
	/**
	 * 修改节点信息
	 * Enter description here ...
	 */
	public function edit() {
		$M = D ( "AdminNode" );
		$info = $M->where ( "id=" . ( int ) $_GET ['id'] )->find ();
		if (empty ( $info ['id'] )) {
			$this->error ( "不存在该节点", U ( 'index' ) );
		}
		$this->assign ( "info", $M->getPid ( $info ) );
		$this->display ("add");
	
	}
	
	/**
	 * 更改节点的排序
	 * Enter description here ...
	 */
	public function opSort(){
		
	  $M = M("AdminNode");
        $datas['id'] = (int) $_POST["id"];
        $datas['sort'] = (int) $_POST["sort"];
        header('Content-Type:application/json; charset=utf-8');
        if ($M->save($datas)) {
            echo json_encode(array('status' => 1, 'info' => "处理成功"));
        } else {
            echo json_encode(array('status' => 0, 'info' => "处理失败"));
        }
	}
	
	/**
	 * 更改节点的状态
	 * Enter description here ...
	 */
	public function opNodeStatus() {
          $id=isset($_POST['id']) ? intval($_POST['id']) : "";
          $status=isset($_POST['status']) ? intval($_POST['status']):"";
          $result=M("AdminNode")->where("id='{$id}'")->setField("status",$status==1 ? 0 : 1);
          if($result){
            $this->success("处理成功!");
          }else{
           $this->error("处理失败!");
          }
    }

}