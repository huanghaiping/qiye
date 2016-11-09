<?php
namespace Home\Model;
use Org\Wechat\Wechat;
use Org\Wechat\WechatAuth;
class WechatModel extends CommonModel {
	
	protected $default_replay = ""; //默认回复文本
	

	/**
	 * 初始化项目开始
	 */
	public function _initialize() {
		parent::_initialize ();
		$this->default_replay = '欢迎您关注' . C ( 'SITE_NAME' ) . '微信公众平台!';
	}
	
	/**
	 * 
	 * 获取微信公众号的信息
	 * @param string	 $t		公众号的唯一标识
	 */
	public function getWechatInfoByToken($t) {
		if (empty ( $t ))
			return false;
		$wechat_info = S ( "wechat_" . $t );
		if (! $wechat_info) {
			$wechat_info = $this->where ( "token='" . $t . "'" )->find ();
			S ( "wechat_" . $t, $wechat_info );
		}
		if ($wechat_info) { //当存在的token的时候，存入session 信息
			session ( "wechat_id", $wechat_info ['id'] );
			session ( "wechat_token", $wechat_info ['wechat_token'] );
		}
		return $wechat_info;
	}
	
	/**
	 * 获取当前公众号的ID
	 */
	public function getWechatId($t) {
		$wechat_id = session ( "wechat_id" );
		if (empty ( $wechat_id )) {
			$wechat_info = $this->getWechatInfoByToken ( $t );
			$wechat_id = $wechat_info ['id'];
		}
		return $wechat_id;
	}
	
	/**
	 * DEMO
	 * @param  Object $wechat Wechat对象
	 * @param  array  $data   接受到微信推送的消息
	 */
	public function reply($wechat, $data) {
		
		$wechat_id = $this->getWechatId ( $data ['t'] );
		switch ($data ['MsgType']) {
			case Wechat::MSG_TYPE_EVENT : //事件的处理
				$this->replayEvent ( $wechat, $wechat_id, $data );
				break;
			
			case Wechat::MSG_TYPE_TEXT : //回复文字
				$this->replyText ( $wechat, $wechat_id, $data ['Content'] );
				break;
			
			case Wechat::MSG_TYPE_IMAGE : //回复图片
				$PicUrl = $data ['PicUrl']; //图片地址
				$MsgId = $data ['MsgId']; //消息ID
				$MediaId = $data ['MediaId'];
				$this->replyText ( $wechat, $wechat_id, $data ['Content'] );
				break;
			
			case Wechat::MSG_TYPE_VOICE : //回复音频
				break;
			case Wechat::MSG_TYPE_VIDEO : //回复视频
				break;
			case Wechat::MSG_TYPE_SHORTVIDEO : //回复短视频
				break;
			case Wechat::MSG_TYPE_LOCATION : //回复位置
				break;
			case Wechat::MSG_TYPE_LINK : //回复链接消息
				break;
			case Wechat::MSG_TYPE_MUSIC : //回复音频
				break;
			case Wechat::MSG_TYPE_NEWS : //回复音频
				break;
			default :
				# code...
				break;
		}
	}
	/**
	 * +------------------------------------------------------
	 * | 判断是否是远程url,否就添加网站域名
	 * +------------------------------------------------------
	 * | @param  string		$url		图片URL
	 */
	public function is_url($url) {
		if (empty ( $url ))
			return '';
		$regex = '/(http:\/\/)|(https:\/\/)/i';
		if (! preg_match ( $regex, $url )) {
			$url = C ( "SITE_URL" ) . $url;
		}
		return $url;
	}
	/**
	 * +------------------------------------------------------
	 * | 发送微信的欢迎语
	 * +------------------------------------------------------
	 * | @param  obj	$wechat			微信公众号对象
	 * | @param  int	$wechat_id		微信公众号ID
	 */
	public function getReply($wechat, $wechat_id) {
		$wechat_welcome_model = D ( "WechatWelcome" );
		$wechat_welcome_info = $wechat_welcome_model->where ( "wechat_id='" . $wechat_id . "'" )->find ();
		if (empty ( $wechat_welcome_info )) {
			$wechat->replyText ( $this->default_replay );
		} else {
			if ($wechat_welcome_info ['welcome_type'] == 1) { //文本
				$wechat->replyText ( $wechat_welcome_info ['content'] );
			} else { //单条图文
				$wechat->replyNewsOnce ( $wechat_welcome_info ['title'], $wechat_welcome_info ['content'], $this->is_url ( $wechat_welcome_info ['url'] ), $this->is_url ( $wechat_welcome_info ['thumb'] ) );
			}
		}
	}
	/**
	 * +------------------------------------------------------
	 * | 根据用户发送的内容进行回复
	 * +------------------------------------------------------
	 * | @param  obj		$wechat			微信公众号对象
	 * | @param  int		$wechat_id		微信公众号ID
	 * | @param  string		$content		用户发送的内容
	 */
	public function replyText($wechat, $wechat_id, $content) {
		$wechat_reply_model = D ( "WechatReply" );
		$wechat_reply_info = $wechat_reply_model->where ( "wechat_id='" . $wechat_id . "' and keyword like '%" . $content . "%'" )->order ( "listorder desc,id desc" )->find ();
		if (empty ( $wechat_reply_info )) {
			$wechat->replyText ( $this->default_replay );
		} else {
			switch ($wechat_reply_info ['reply_id']) {
				case 1 :
					$wechat->replyNewsOnce ( $wechat_reply_info ['title'], $wechat_reply_info ['description'], $this->is_url ( $wechat_reply_info ['url'] ), $this->is_url ( $wechat_reply_info ['thumb'] ) );
					
					break; //单条图文
				case 2 : //多条图文
					$mult_ids = $wechat_reply_info ['mult_ids'];
					$admin_wechat_model = D ( ADMIN_NAME."/Wechat" );
					$ids_info = $admin_wechat_model->getMulitReplyList ( $mult_ids );
					$ids = getSubByKey ( $ids_info, "id" );
					$replay_list = $admin_wechat_model->getReplyListByIds ( $ids, '', $ids_info );
					$row = array ();
					if ($replay_list) {
						foreach ( $replay_list as $key => $value ) {
							$row [] = array ($value ['title'], $value ['description'], $this->is_url ( $value ['url'] ), $this->is_url ( $value ['thumb'] ) );
						}
					}
					unset ( $replay_list );
					$wechat->replyNews ( $row );
					break;
				case 3 :
					$wechat->replyText ( $wechat_reply_info ['content'] );
					break; //文本回复
				default :
					$wechat->replyText ( $this->default_replay );
					break;
			}
		}
	
	}
	/**
	 * +------------------------------------------------------
	 * | 事件的回复,关注/取消关注后欢迎语信息对应后台的欢迎语
	 * +------------------------------------------------------
	 * | @param  obj		$wechat			微信公众号对象
	 * | @param  int		$wechat_id		微信公众号ID
	 * | @param  string		$data			回复的内容
	 */
	public function replayEvent($wechat, $wechat_id, $data) {
		if (empty ( $data ))
			return false;
		switch ($data ['Event']) {
			case Wechat::MSG_EVENT_SUBSCRIBE : //用户关注公众号
				$this->getReply ( $wechat, $wechat_id );
				break;
			case Wechat::MSG_EVENT_UNSUBSCRIBE : //用户取消关注公众号
				break;
			case Wechat::MSG_EVENT_CLICK : //自定义菜单是点击事件后触发的内容
				$this->replyText ( $wechat, $wechat_id, $data ['EventKey'] );
				break;
			case Wechat::MSG_EVENT_SCANCODE_WAITMSG :
				break;
			default :
				$wechat->replyText ( $this->default_replay );
				break;
		}
	}
	
	/**
	 * +------------------------------------------------------
	 * 获取自定义菜单菜单
	 * +------------------------------------------------------
	 * @param	int		$wecahtId		微信公众号ID
	 */
	public function getMenu($wecahtId) {
		$config = F ( "wechat_Menu_" . $wecahtId );
		if (! $config) {
			$category = new \Org\Util\Category ( 'WechatMenu', array ("id", "parent_id", "menu_name", "fullname", "replay_keyword", "url", "status", "sort", "wechat_event" ) );
			$config = $category->getList ( array ("wechat_id" => $wecahtId, "status" => 1 ), 0, "sort desc,id desc" ); //获取分类结构
			$cat = array ();
			foreach ( $config as $value ) {
				$cat [$value ['id']] = $value;
			}
			//保存配置
			F ( "wechat_Menu_" . $wecahtId, $cat );
		}
		return $config;
	}
	/**
	 * +------------------------------------------------------
	 * 处理自定义的格式数据
	 * +------------------------------------------------------
	 * @param	Array		$menu_list		菜单的列表数据
	 */
	public function dealDataByMenu($menu_list) {
		$memu_data = array ();
		foreach ( $menu_list as $key => $value ) {
			$key = isset ( $value ['replay_keyword'] ) ? $value ['replay_keyword'] : "";
			$media_id = isset ( $value ['media_id'] ) ? $value ['media_id'] : "";
			
			switch ($value ['wechat_event']) {
				case "none" :
					$data = array ("name" => $value ['menu_name'] );
					break;
				case "view" :
					$data = array ("name" => $value ['menu_name'], "type" => $value ['wechat_event'], "url" => $value ['url'] );
					break;
				case "media_id" :
					$data = array ("name" => $value ['menu_name'], "type" => $value ['wechat_event'], "media_id" => $media_id );
					break;
				case "view_limited" :
					$data = array ("name" => $value ['menu_name'], "type" => $value ['wechat_event'], "media_id" => $media_id );
					break;
				default :
					$data = array ("name" => $value ['menu_name'], "type" => $value ['wechat_event'], "key" => $key );
					break;
			}
			if ($value ['parent_id'] == 0) {
				$memu_data [$value ['id']] = $data;
			} else {
				$memu_data [$value ['parent_id']] ['sub_button'] [] = $data;
			}
		}
		return array_values ( $memu_data );
	
	}

}