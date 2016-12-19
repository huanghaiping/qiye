<?php
namespace Jzadmin\Model;
class UserModel extends CommonModel {
	
	/**
	 * +--------------------------------------
	 * 获取用户信息
	 * +-------------------------------------
	 * @param string	 $uids		用户uid
	 */
	public function getUserByIds($uids) {
		if (empty ( $uids ))
			return false;
		$user_model=D("Home/User");
		return $user_model->getUserInfoByUids($uids);
	
	}
}