<?php
namespace Home\Controller;
use Org\Wechat\Wechat;
use  Org\Wechat\WechatAuth;
class WechatController extends CommonController {
	
	protected $appId = ""; //AppID(应用ID)
	protected $token = ""; //微信后台填写的TOKEN
	protected $encodingAESKey = ""; //消息加密KEY（EncodingAESKey）
	protected $appSecret="";
	protected $wechatModel="";
	
	/**
	 * 初始化项目的值
	 * Enter description here ...
	 */
	public function _initialize() {
		parent::_initialize();
		$t=isset($_GET['t']) ? addSlashesFun($_GET['t']) : "";
		$this->wechatModel=D("Wechat");
		$weixin_config=$this->wechatModel->getWechatInfoByToken($t);
		if (empty($t)||!$weixin_config){
			$this->error("非法请求");
		}
		if ($weixin_config){
			$this->appId=$weixin_config['appid'];
			$this->token=$weixin_config['wechat_token'];
			$this->encodingAESKey =$weixin_config['encodingaeskey'];
			$this->appSecret=$weixin_config['appsecret'];
		}
		
	}
	/**
	 * 微信的入口文件
	 * 
	 */
	public function index($id = '') {
		//调试
		try {
			$wechat = new Wechat ( $this->token, $this->appId, $this->encodingAESKey ); /* 加载微信SDK */
			/* 获取请求信息 */
			$data = $wechat->request ();
			
			if ($data && is_array ( $data )) {
				$data=array_merge($data,$_GET);
				//记录微信推送过来的数据
				file_put_contents ( INCLUDE_PATH.'/data.json', json_encode ( $data ) );
				
				/* 响应当前请求(自动回复) */
				//$wechat->response("欢迎光临".$data['Content'].",");

				//执行Demo
				$this->wechatModel->reply ( $wechat, $data );
			}
		} catch (\Exception $e ) {
			file_put_contents ( INCLUDE_PATH.'error.json', json_encode ( $e->getMessage () ) );
		}
	}
	
	/**
	 * +------------------------------------------------------
	 * 微信公众号之生成自定义菜单
	 * +------------------------------------------------------
	 */
	public function menucreate(){ 
		
		$wechat_id=isset($_GET['wechat_id']) ? intval($_GET['wechat_id']) : "";
		$this->token = session("AccessToken_".$wechat_id);
        if($this->token ){
            $wechat_auth = new WechatAuth($this->appId, $this->appSecret, $this->token);
        } else {
            $wechat_auth  = new WechatAuth($this->appId, $this->appSecret);
            $token  = $wechat_auth->getAccessToken();
            session(array('expire' => $token['expires_in']));
            session("AccessToken_".$wechat_id, $token['access_token']);
            $this->token=$token['access_token'];
        }

        //先删除所有菜单
        $is_delete=$wechat_auth->menuDelete();
        $memu_list=$this->wechatModel->getMenu($wechat_id);
        if (empty($memu_list)){
        	$this->error("请先添加菜单");
        }
        $menu_data=$this->wechatModel->dealDataByMenu($memu_list);
		//创建菜单
		if (!empty($menu_data)){
			$result=$wechat_auth->menuCreate($menu_data);
			if ($result['errmsg']=='ok'&&$result['errcode']==0){
				$this->success("更新成功");
			}else{
				$this->error("更新失败,错误码:".$result['errcode'].";错误信息:".$result['errmsg']);
			}
		}else{
			$this->error("更新失败");
		}
	}
}