<?php
namespace Home\Model;
class UserGroupModel extends CommonModel {
	
	/**
	 * +-----------------------------------------------
	 * 获取用户等级制度
	 * +-------------------------------------------------	
	 */
	public function getUseGroup() {
		$user_level = F ( "user_group_" . $this->lang );
		if (! $user_level) {
			$user_level = $this->where ( "lang='" . $this->lang . "'" )->order ( "sortby asc" )->select ();
			F ( "user_group_" . $this->lang, $user_level );
		}
		return $user_level;
	}
	
	/**
	 * +-----------------------------------------------
	 * 根据等级的值获取等级名称和折扣
	 * +-----------------------------------------------
	 * 
	 * @param		int 		$group_id		用户等级ID
	 * @return		Array		$gropuInfo		等级信息
	 */
	public function getGroupByGroupId($group_id = 0) {
		$group_list = $this->getUseGroup ();
		$gropuInfo = array ();
		foreach ( $group_list as $value ) {
			if ($value ['sortby'] == $group_id) {
				$gropuInfo = $value;
				break;
			}
		}
		return $gropuInfo;
	}
	
	/**
	 * +-----------------------------------------------
	 * 修改用户的等级(当用户购买成功后)
	 * +-----------------------------------------------
	 * 
	 * @param		int 		$uid		用户UID
	 * @return		bool		
	 */
	public function EditUserGroupByUid($uid) {
		if (empty ( $uid ))
			return false;
		$user_model = D ( "User" );
		$result = $user_model->where ( "uid='" . $uid . "'" )->setInc ( "buy_num", 1 ); //记录用户的购买次数
		if ($result) {
			$user_info = $user_model->getUserInfoByUid ( $uid );
			$group_list = $this->getUseGroup (); //获取所有的等级
			foreach ( $group_list as $value ) {
				if ($user_info ['buy_num'] == $value ['buy_num'] && $user_info ['group_id'] < $value ['sortby']) {
					$this->where ( "uid='" . $uid . "'" )->setField ( "group_id", $value ['sortby'] );
					static_cache ( 'user_info_' . $uid, null );
					S ( "ui_" . $uid, null );
					break;
				}
			}
		}
	
	}

}