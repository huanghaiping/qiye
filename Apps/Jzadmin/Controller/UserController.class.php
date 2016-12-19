<?php
namespace Jzadmin\Controller;
class  UserController extends CommonController{
	
	/**
	 * +----------------------------------------------
	 * 查看会员列表
	 * +----------------------------------------------
	 */
	public function index(){
		$keyword = isset ( $_REQUEST ['keyword'] ) ? $_REQUEST ['keyword'] : "";
		$request ['usertype'] = isset ( $_REQUEST ['usertype'] ) ? ($_REQUEST ['usertype']) : "";
		$request ['status'] = isset ( $_REQUEST ['status'] ) ? ($_REQUEST ['status']) : "";
		$condition = $param = array ();
		$startTime=isset($_REQUEST['startTime']) ?  $_REQUEST['startTime'] : "";
		$endTime=isset($_REQUEST['endTime']) ?  $_REQUEST['endTime'] : "";	
		if (! empty ( $keyword )) {
			if (checkEmailFormat ( $keyword )) {
				$condition [] = " email like '%" . $keyword . "%'";
			} else {
				$condition [] = " nickname like '%" . $keyword . "%'";
			}
			$param ['keyword'] = $keyword;
			$this->assign ( "keyword", $keyword );
		}
	 
		if (! empty ( $startTime )) {
			$startTime  =is_numeric($startTime) ? Date("Y-m-d H:i:s",$startTime) : $startTime;
			$time_sql=" reg_time >" . strtotime($startTime) . "";
			$param ['startTime'] = strtotime($startTime);
			$this->assign ( "startTime", $startTime );
			if (! empty ( $endTime )) {
				$endTime  =is_numeric($endTime) ? Date("Y-m-d H:i:s",$endTime) : $endTime;
				$time_sql.=" and reg_time < " . strtotime($endTime) . "";
				$param ['endTime'] = strtotime($endTime);
				$this->assign ( "endTime", $endTime );
			}
			$condition [] = " (".$time_sql.") ";
		}
		
		foreach ( $request as $key => $value ) {
			if ($value != "") {
				$condition [] = " {$key}='{$value}'";
				$this->assign ( $key, $value );
				$param [$key] = $value;
			}
		}
		$sql_where = "";
		if (count ( $condition ) > 0) {
			$sql_where = "where " . join ( " AND ", $condition );
		}
		$prix_name = C ( "DB_PREFIX" );
		$field=" uid,nickname,email,mobile,status,faceurl,usertype,login_time,reg_time ";
		$sql_count = "select ".$field." from " . $prix_name . "user u left join " . $prix_name . "user_info m on u.uid=m.mid " . $sql_where . " order by uid desc ";
		$result = $this->model_name->getPageData ( $sql_count, $param, 20 );
		$this->assign ( "userlist", $result ['data'] );
		$this->assign ( "page", $result ['page'] );
		$this->display ();
	}
	
	/**
	 * +------------------------------------
	 * 便携式更改会员状态
	 * +------------------------------------
	 */
	public function updateStaus() {
		$id = isset ( $_POST ['id'] ) ? $_POST ['id'] : "";
		$value = isset ( $_POST ['value'] ) ? intval ( $_POST ['value'] ) : "";
		$type = isset ( $_POST ['type'] ) ? $_POST ['type'] : "";
		if (empty ( $id )) {
			$this->error ( "数据请求错误!" );
		}
		$sql = "uid ='{$id}'";
		if (is_array ( $id )) { 
			$id = implode(",", $id);
			$sql = "uid in({$id})";
		}
		$value = $value == 1 ? 0 : 1;
		$result = $this->model_name->where ( $sql )->setField ( $type, $value );
		if ($result) {
			//清楚缓存
			if(strpos($id,",")){
				$idStr=explode(",", $id);
			}else{
				$idStr=array($id);
			}
			foreach ($idStr as $value){
				S ( 'userDetailInfo_' . $value, null );
				S ( 'ui_' . $value, null );
				static_cache ( 'user_info_' . $value,null );
			}
			$this->success ( "处理成功" );
		} else {
			$this->error ( "处理失败!" );
		}
	}
	
	/**
	 * +------------------------------------
	 * 修改用户资料
	 * +------------------------------------
	 */
	public function edit() {
		$model = $this->model_name;
		if (IS_POST) {
			$id = isset ( $_POST ['id'] ) ? intval ( $_POST ['id'] ) : 0;
			if (empty ( $id )) {
				$this->error ( "数据请求错误!" );
			}
			$password = isset ( $_POST ['password'] ) && ! empty ( $_POST ['password'] ) ? md5(md5 ( $_POST ['password'] )) : "";
			$data ['score'] = isset($_POST ['score']) ? $_POST ['score'] : "";
			$data ['gold'] = isset($_POST ['gold']) ? $_POST ['gold'] : "";
			$data ['status'] = isset($_POST ['status']) ? intval($_POST ['status']) : "";
			if(is_array($_POST ['flag'])){
				$data ['flag'] = join(",", $_POST ['flag']);
			}else{
				$data ['flag']=isset($_POST ['flag']) ? $_POST ['flag'] : "";
			}
			if (! empty ( $password )) {
				$data ['password'] = $password;
			}
			$result = $model->where ( "uid='{$id}'" )->save ( $data );
			if ($result) {
				D ( "UserInfo" )->where ( "mid={$id}" )->setField ( "update_time", time () );
				//清楚缓存
				S ( 'userDetailInfo_' . $id, null );
				S ( 'ui_' . $id, null );
				$this->success ( "修改成功!", U('index') );
			} else {
				$this->error ( "修改失败!" );
			}
		
		} else {
			$id = isset ( $_GET ["id"] ) ? intval ( $_GET ["id"] ) : "";
			if (empty ( $id )) {
				$this->error ( "数据请求错误!" );
			}
			$field=" d.mid,d.sex,d.province,d.city,d.area,d.constellation,d.update_time,d.reg_time ";
			$sql = "select m.*,{$field} from " . C ( "DB_PREFIX" ) . "user m join " . C ( "DB_PREFIX" ) . "user_info d on m.uid=d.mid where m.uid='{$id}' limit 1";
			$result = $model->query ( $sql );
			if ($result) {
				$result=$result[0];
				if(!empty($result['province'])){
					$Areas = D ( 'Area' ); //获取地区
					$area_str=$result['province'].",".$result['city'].",".$result['area'];
					$area_result = $Areas->where ( "id in({$area_str})" )->field ( 'id,area_name' )->select ();
					$this->assign ( "area_result", $area_result );
				}
				$this->assign ( "info", $result);
				$this->display ();
			} else {
				$this->error ( "数据请求错误,id不存在!" );
			}
		}
	}
}