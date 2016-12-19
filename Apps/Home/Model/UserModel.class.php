<?php
namespace Home\Model;
class UserModel extends CommonModel {
	
	protected $_fields = " uid,email,mobile,nickname,status,mtype,usertype,login_time,faceurl,money,group_id ";
	
	/**
	 * +-----------------------------------------------------
	 * 注册用户资料
	 * +-----------------------------------------------------
	 * 
	 * @param 	Array 	$data			用户主信息资料
	 * @param 	Array 	$user_data		用户详细信息
	 */
	public function reg_data($data, $user_data = array()) {
		if (empty ( $data ))
			return false;
		$data ['login_time'] = time ();
		$data ['email'] = isset ( $data ['email'] ) ? $data ['email'] : "";
		$data ['nickname'] = isset ( $data ['nickname'] ) ? $data ['nickname'] : "";
		$data ['mobile'] = isset ( $data ['mobile'] ) ? $data ['mobile'] : "";
		$data ['faceurl'] = isset ( $data ['faceurl'] ) ? $data ['faceurl'] : "";
		$data ['sign'] = isset ( $data ['sign'] ) ? $data ['sign'] : "";
		
		$user_data ['loation'] = isset ( $user_data ['loation'] ) ? $user_data ['loation'] : "";
		$user_data ['province'] = isset ( $user_data ['province'] ) ? $user_data ['province'] : "";
		$user_data ['city'] = isset ( $user_data ['city'] ) ? $user_data ['city'] : "";
		$user_data ['area'] = isset ( $user_data ['area'] ) ? $user_data ['area'] : "";
		$user_data ['constellation'] = isset ( $user_data ['constellation'] ) ? $user_data ['constellation'] : "";
		$user_data ['userip'] = isset ( $user_data ['userip'] ) ? $user_data ['userip'] : "";
		$user_data ['is_email'] = isset ( $user_data ['is_email'] ) ? $user_data ['is_email'] : "";
		$user_data ['is_tel'] = isset ( $user_data ['is_tel'] ) ? $user_data ['is_tel'] : "";
		$user_data ['truename'] = isset ( $user_data ['truename'] ) ? $user_data ['truename'] : "";
		$user_data ['company'] = isset ( $user_data ['company'] ) ? $user_data ['company'] : "";
		$user_data ['position'] = isset ( $user_data ['position'] ) ? $user_data ['position'] : "";
		$user_data ['good_skills'] = isset ( $user_data ['good_skills'] ) ? $user_data ['good_skills'] : "";
		$user_data ['description'] = isset ( $user_data ['description'] ) ? $user_data ['description'] : "";
		$user_data ['reg_time'] = isset ( $user_data ['reg_time'] ) ? $user_data ['reg_time'] : "";
		$user_data ['update_time'] = isset ( $user_data ['update_time'] ) ? $user_data ['update_time'] : "";
		
		$user_id = $this->add ( $data );
		if ($user_id) {
			$user_data ['mid'] = $user_id;
			$user_data ['reg_time'] = time ();
			$user_data ['userip'] = get_client_ip ();
			$user_info_model = D ( "UserInfo" );
			$result = $user_info_model->add ( $user_data );
			if ($result) { //注册成功
				// 添加默认关注用户
				return $user_id;
			} else {
				$this->where ( "uid={$user_id}" )->delete (); //删除已经插入成功的数据进行回调	
				return false;
			}
		} else {
			return false;
		}
	}
	
	/**
	 * +-----------------------------------------------------
	 * 检查指定字段的值是否存在
	 * +-----------------------------------------------------
	 * 
	 * @param 	string 	$email 	用户的邮箱
	 * @return 					成功返回对应邮箱的用户id，失败返回false
	 */
	public function checkUidByField($field, $value) {
		$map [$field] = $value;
		$data = $this->where ( $map )->field ( $this->_fields )->limit ( 1 )->find ();
		return $data ? $data ['uid'] : false;
	}
	
	/**
	 * +-----------------------------------------------------
	 * 获取指定会员的用户信息
	 * +-----------------------------------------------------
	 * 
	 * @param int  $uid
	 */
	public function getUserInfoByUid($uid) {
		
		$uid = intval ( $uid );
		if ($uid <= 0) {
			return false;
		}
		if ($user = static_cache ( 'user_info_' . $uid )) {
			return $user;
		}
		// 查询缓存数据
		$user = S ( 'ui_' . $uid );
		if (! $user) {
			$map ['uid'] = $uid;
			$user = $this->_getUserInfo ( $map );
		}
		static_cache ( 'user_info_' . $uid, $user );
		return $user;
	}
	
	/**
	 * +-----------------------------------------------------
	 * 根据UID批量获取多个用户的相关信息
	 * +-----------------------------------------------------
	 * 
	 * @param 	array 	$uids	用户UID数组
	 * @return  array 	$row	指定用户的相关信息
	 */
	public function getUserInfoByUids($uids) {
		! is_array ( $uids ) && $uids = explode ( ',', $uids );
		$row = $map = array ();
		foreach ( $uids as $v ) {
			$row [$v] = S ( 'ui_' . $v );
			if (empty ( $row [$v] )) {
				$map ['uid'] = $v;
				$row [$v] = $this->_getUserInfo ( $map );
			}
		}
		return $row;
	}
	
	/**
	 * +-----------------------------------------------------
	 * 获取指定用户的相关信息
	 * +-----------------------------------------------------
	 *
	 * @param 	array 	$map		查询条件
	 * @return  array 	$user		指定用户的相关信息
	 */
	private function _getUserInfo($map) {
		$uid = isset ( $map ['uid'] ) ? intval ( $map ['uid'] ) : "";
		if (empty ( $uid ))
			return false;
		$user = S ( "ui_" . $uid );
		if (! $user) {
			$user = $this->where ( $map )->field ( $this->_fields )->find ();
			S ( 'ui_' . $uid, $user, 3600 );
		}
		return $user;
	}
	
	/**
	 * +-----------------------------------------------------
	 * 获取用户的所有资料信息
	 * E+-----------------------------------------------------
	 * 
	 * @param 	int 	$uid	用户uid
	 */
	public function getUserDetailByUid($uid) {
		if (empty ( $uid ))
			return false;
		$field = " m.uid,m.email,m.mobile,m.nickname,m.faceurl,m.group_id,m.money,m.login_time, d.truename,d.sex,d.loation,d.province,d.city,d.area,d.constellation,d.is_email,d.is_tel,d.reg_time,d.userip,d.description ";
		$sql = "SELECT " . $field . " from " . $this->tablePrefix . "user m left join " . $this->tablePrefix . "user_info d on m.uid=d.mid where m.uid='{$uid}' limit 1";
		$result = $this->query ( $sql );
		return $result ? $result [0] : false;
	}
	
	/**
	 * +-----------------------------------------------------
	 * 获取所有的星座
	 * E+-----------------------------------------------------
	 * 
	 * @param 	int 	$ConstellationId	星座编号
	 */
	public function getConstellation($ConstellationId = '') {
		
		$constellation = array ('1' => '白羊座', '2' => '金牛座', '3' => '双子座', '4' => '巨蟹座', '5' => '狮子座', '6' => '处女座', '7' => '天秤座', '8' => '天蝎座', '9' => '射手座', '10' => '摩羯座', '11' => '水瓶座', '12' => '双鱼座' );
		return $ConstellationId == "" ? $constellation : $constellation [$ConstellationId];
	}
}