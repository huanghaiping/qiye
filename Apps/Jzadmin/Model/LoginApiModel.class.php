<?php
namespace Jzadmin\Model;
/**
 * +-------------------------------------------
 * 登录的模型类
 * +-------------------------------------------
 * @author Alan
 */
class LoginApiModel extends CommonModel {
	
	protected $_validate = array (array ('name', 'require', '登录接口名称不能为空', 1 ), array ('typename', 'require', '登录接口标识不能为空', 1 ), array ('typename', '', '登录接口标识已经存在！', 0, 'unique', 1 ), array ('appkey', 'require', 'APP key不能为空', 1 ), array ('appsecret', 'require', 'App secret不能为空', 1 ) );
	
	/**
	 * +-----------------------------------------
	 * 登录接口模型字段
	 * +-----------------------------------------
	 * @param Array $postData	POST提交上来的数据
	 * 
	 * @return Array 返回过滤后的数组
	 */
	public function createData($postData) {
		$data = array ();
		$data ['ctime'] = time ();
		$data ['name'] = isset ( $postData ['name'] ) ? addSlashesFun ( $postData ['name'] ) : "";
		$data ['typename'] = isset ( $postData ['typename'] ) ? addSlashesFun ( $postData ['typename'] ) : "";
		$data ['appkey'] = isset ( $postData ['appkey'] ) ? addSlashesFun ( $postData ['appkey'] ) : "";
		$data ['appsecret'] = isset ( $postData ['appsecret'] ) ? addSlashesFun ( $postData ['appsecret'] ) : "";
		$data ['description'] = isset ( $postData ['description'] ) ? addSlashesFun ( $postData ['description'] ) : "";
		$data ['status'] = isset ( $postData ['status'] ) ? intval ( $postData ['status'] ) : "";
		$data ['listorder'] = isset ( $postData ['listorder'] ) ? intval ( $postData ['listorder'] ) : "";
		$data ['id'] = isset ( $postData ['id'] ) ? intval ( $postData ['id'] ) : "";
		return $data;
	}
	
	/**
	 * +-----------------------------------------
	 * 获取所有的登录接口
	 * +-----------------------------------------
	 */
	public function getLoginList() {
		$login_list = F ( 'Login_api' );
		if (! $login_list) {
			$row = $this->field ( "id,typename,appkey,appsecret,description" )->order ( "listorder desc, id desc" )->select ();
			if ($row) {
				$host=C("SITE_URL");
				foreach ( $row as $value ) {
					$value['callback']=$host.'/Login/callback?type='.$value['typename'];
					$login_list [$value ['typename']] = $value;
				}
				F ( 'Login_api', $login_list );
			}
			unset ( $row );
		}
		return $login_list;
	}
	
	/**
	 * +-----------------------------------------
	 * 根据登录接口的唯一标识获取登录的信息
	 * +-----------------------------------------
	 * @param  string $typeName			登录接口唯一性
	 * @return Array  $login_list		登录的配置信息
	 */
	public function getInfoByTypeName($typeName) {
		if (empty ( $typeName ))
			return false;
		$typeName=strtolower($typeName);
		$login_list = $this->getLoginList ();
		if ($login_list && array_key_exists ( $typeName, $login_list )) {
			return $login_list [$typeName];
		} else {
			return false;
		}
	}
}