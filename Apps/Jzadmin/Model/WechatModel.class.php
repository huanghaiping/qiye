<?php
namespace Jzadmin\Model;
class WechatModel extends CommonModel {
	
	protected $_validate = array (array ('name', 'require', '公众号名称不能为空', 1 ), array ('originid', 'require', '公众号原始ID不能为空', 1 ), array ('wechat_name', 'require', '微信号不能为空', 1 ) );
	
	/**
	 * +-----------------------------------------
	 * 验证公众号模型字段
	 * +-----------------------------------------
	 * @param Array $postData	POST提交上来的数据
	 * 
	 * @return Array 返回过滤后的数组
	 */
	public function createData($postData) {
		$data = array ();
		$data ['ctime'] = time ();
		$data ['name'] = isset ( $postData ['name'] ) ? addSlashesFun ( $postData ['name'] ) : "";
		$data ['originid'] = isset ( $postData ['originid'] ) ? addSlashesFun ( $postData ['originid'] ) : "";
		$data ['wechat_name'] = isset ( $postData ['wechat_name'] ) ? addSlashesFun ( $postData ['wechat_name'] ) : "";
		$data ['wechat_token'] = isset ( $postData ['wechat_token'] ) ? addSlashesFun ( $postData ['wechat_token'] ) : "";
		$data ['appid'] = isset ( $postData ['appid'] ) ? addSlashesFun ( $postData ['appid'] ) : "";
		$data ['appsecret'] = isset ( $postData ['appsecret'] ) ? addSlashesFun ( $postData ['appsecret'] ) : "";
		$data ['encodingaeskey'] = isset ( $postData ['encodingaeskey'] ) ? addSlashesFun ( $postData ['encodingaeskey'] ) : "";
		$data ['wechat_type'] = isset ( $postData ['wechat_type'] ) ? intval ( $postData ['wechat_type'] ) : "";
		$data ['status'] = isset ( $postData ['status'] ) ? intval ( $postData ['status'] ) : "";
		 
		if (empty($postData['id'])){
			$data ['token']=substr($this->getUniqId(),0,16);
		}
		$file_data = $this->uploadImg ( array ('wechat_thumb' ), 'wechat' );
		if (! empty ( $file_data ['wechat_thumb'] )) {
			$data ['wechat_thumb'] = $file_data ['wechat_thumb'];
		}
		return $data;
	}
	
	/**
	 * 获取微信的类型
	 * 
	 * @param  int 		$typeid		类型ID
	 * @return Array 	$row		类型的数组
	 */
	public function getWechatType($typeid = 0) {
		$wechatType = array ();
		$wechatType [1] = '订阅号';
		$wechatType [2] = '认证订阅号/普通服务号';
		$wechatType [3] = '认证服务号';
		return empty ( $typeid ) ? $wechatType : $wechatType [$typeid];
	}
	
	/**
	 * +------------------------------------------------------
	 * 生成微信的Token
	 * +------------------------------------------------------
	 */
	public function getUniqId() {
		return md5 ( str_replace ( ".", "", uniqid ( 'wx', TRUE ) ) );
	}
	
	/**
	 * +------------------------------------------------------
	 * 获取所有的微信公众号
	 * +------------------------------------------------------
	 * @param	Int			$wechatId	微信公众号ID
	 * @return  Array/Int	返回数组或者单条微信公众号信息
	 */
	public function getAllWechat($wechatId = '') {
		$wechat_list = array ();
		$wechat_list = F ( "WechatList" );
		if (! $wechat_list) {
			$wechat = $this->order ( "id desc" )->select ();
			if ($wechat) {
				foreach ( $wechat as $key => $value ) {
					$value ['wechat_type_name'] = $this->getWechatType ( $value ['wechat_type'] );
					$wechat_list [$value ['id']] = $value;
				}
				F ( "WechatList", $wechat_list );
			}
		}
		return empty ( $wechatId ) ? $wechat_list : $wechat_list [$wechatId];
	}
	
	/**
	 * +------------------------------------------------------
	 * 微信菜单的事件类型
	 * +------------------------------------------------------
	 */
	public function menuType($menu_type = '') {
		$menuType = array ();
		$menuType ['none'] = '无事件的一级菜单';
		$menuType ['click'] = '点击推事件';
		$menuType ['view'] = '跳转URL';
		$menuType ['scancode_push'] = '扫码推事件';
		$menuType ['scancode_waitmsg'] = '扫码推事件且弹出提示';
		$menuType ['pic_sysphoto'] = '弹出系统拍照发图';
		$menuType ['pic_photo_or_album'] = '弹出拍照或者相册发图';
		$menuType ['pic_weixin'] = '弹出微信相册发图器';
		$menuType ['location_select'] = '弹出地理位置选择器';
		$menuType ['media_id'] = '下发消息（除文本消息）';
		$menuType ['view_limited'] = '跳转图文消息URL';
		return empty ( $menu_type ) ? $menuType : $menuType [$menu_type];
	}
	
	/**
	 * +------------------------------------------------------
	 * 更新菜单缓存
	 * +------------------------------------------------------
	 * @param	int		$wecahtId		微信公众号ID
	 */
	public function updateMenuCache($wecahtId) {
		F ( "wechat_Menu_" . $wecahtId, null );
		$category = new \Org\Util\Category ( 'WechatMenu', array ("id", "parent_id", "menu_name", "fullname", "replay_keyword", "url", "status", "sort", "wechat_event" ) );
		$config = $category->getList ( array ("wechat_id" => $wecahtId, "status" => 1 ), 0, "sort desc,id desc" ); //获取分类结构
		$cat = array ();
		foreach ( $config as $value ) {
			$cat [$value ['id']] = $value;
		}
		unset ( $config );
		//保存配置
		F ( "wechat_Menu_" . $wecahtId, $cat );
	}
	/**
	 * +------------------------------------------------------
	 * 获取微信菜单
	 * +------------------------------------------------------
	 * @param	int		$wecahtId		微信公众号ID
	 */
	public function getMenuType($wecahtId) {
		$category = new \Org\Util\Category ( 'WechatMenu', array ("id", "parent_id", "menu_name", "fullname", "replay_keyword", "url", "status", "sort", "ctime", "wechat_event" ) );
		$config = $category->getList ( array ("wechat_id" => $wecahtId ), 0, "sort desc,id desc" ); //获取分类结构
		return $config;
	}
	/**
	 * +------------------------------------------------------
	 * 获取微信自定义回复的匹配类型
	 * +------------------------------------------------------
	 * @param	int		$keywordType		微信公众号匹配类型ID
	 */
	public function getKeywordType($keywordType = '') {
		$keyword_type = array ();
		$keyword_type [0] = '完全匹配';
		$keyword_type [1] = '左边匹配';
		$keyword_type [2] = '右边匹配';
		$keyword_type [3] = '模糊匹配';
		return ( string ) $keywordType != '' ? $keyword_type [$keywordType] : $keyword_type;
	}
	/**
	 * +------------------------------------------------------
	 * 获取微信自定义回复的所有图文
	 * +------------------------------------------------------
	 * @param	int		$keywordType		微信公众号匹配类型ID
	 */
	public function replyList($wechatId) {
		$wechat_reply_model = D ( "WechatReply" );
		$result = $wechat_reply_model->where ( "wechat_id='" . $wechatId . "' and reply_id=1" )->field ( "id,title,thumb" )->order ( "listorder desc,id desc" )->select ();
		return $result;
	}
	/**
	 * +------------------------------------------------------
	 * 获取微信自定义回复的所有图文
	 * +------------------------------------------------------
	 * @param	int		$ids				图文消息ID
	 * @param	int		$orderby			排序方式
	 * @param	int		$auth_orderby		自定义排序
	 */
	public function getReplyListByIds($ids, $orderby = '',$auth_orderby=array()) {
		if (empty ( $ids ))
			return false;
		 
		$wechat_reply_model = D ( "WechatReply" );
		$ids = is_array ( $ids ) ? implode ( ",", $ids ) : $ids;
		$ids=rtrim($ids,",");  
		$result = $wechat_reply_model->where ( "id in (" . $ids . ")" )->field ( "id,title,thumb,description,url" )->order($orderby)->select ();
		$row = array ();
		if ($result) {
			$sort=array();
			foreach ( $result as $key => $value ) {
				$sort[]=$value['orderby']=  !empty($auth_orderby)&&array_key_exists($value['id'], $auth_orderby)  ? $auth_orderby[$value['id']]['sort'] : 0;
				$row [$value ['id']] = $value;
			}
			array_multisort($sort,SORT_DESC,$row);
		}
		unset ( $result );
		return $row;
	}
	/**
	 * +------------------------------------------------------
	 * 获取微信自定义多图文解析
	 * +------------------------------------------------------
	 * @param	int		$mult_ids		图文消息ID
	 */
	public function getMulitReplyList($mult_ids) {
		if (empty ( $mult_ids ))
			return false;
		$mult_ids = rtrim ( $mult_ids, "#" );
		$text_info = explode ( "#", $mult_ids );
		$row = array ();
		foreach ( $text_info as $key => $value ) {
			$ids = explode ( "_", $value );
			$row [$ids[0]] = array('id'=>$ids[0],'info'=>$ids [0] . "_" . $ids [1] ,'sort'=>$ids [1]);
		}
		return $row;
	}

}