<?php
// +----------------------------------------------------------------------
// | 微信客户端登录接口 [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Alan <451648237@qq.com> <http://www.zhansu.cn>
// +----------------------------------------------------------------------
// | WxSDK.class.php 2017-7-5
// +----------------------------------------------------------------------

use Org\ThinkSdk\ThinkOauth;
class WxSDK extends ThinkOauth {
	/**
	 * 获取requestCode的api接口
	 * @var string
	 */
	protected $GetRequestCodeURL = 'https://open.weixin.qq.com/connect/oauth2/authorize';
	
	/**
	 * 获取access_token的api接口
	 * @var string
	 */
	protected $GetAccessTokenURL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
	
	/**
	 * API根路径
	 * @var string
	 */
	protected $ApiBase = 'https://api.weixin.qq.com/';
		/**
	 * 获取request_code的额外参数 URL查询字符串格式
	 * @var srting
	 */
	protected $Authorize = 'scope=snsapi_userinfo&state=STATE#wechat_redirect';
	
	/**
	 * 组装接口调用参数 并调用接口
	 * @param  string $api    微博API
	 * @param  string $param  调用API的额外参数
	 * @param  string $method HTTP请求方法 默认为GET
	 * @return json
	 */
	public function call($api, $param = '', $method = 'GET', $multi = false) {
		/* 新浪微博调用公共参数 */
		$params = array ('access_token' => $this->Token ['access_token'] );
		
		$vars = $this->param ( $params, $param ); 
		$data = $this->http ( $this->url ( $api, '' ), $vars, $method, array (), $multi );
		return json_decode ( $data, true );
	}
	
	/**
	 * 解析access_token方法请求后的返回值
	 * @param string $result 获取access_token的方法的返回值
	 */
	protected function parseToken($result, $extend) {
		$data = json_decode ( $result, true );
		if ($data ['access_token'] && $data ['expires_in'] && $data ['openid']) {
			return $data;
		} else
			throw new Exception ( "获取微信ACCESS_TOKEN出错：{$data['error']}" );
	}
	
	/**
	 * 获取当前授权应用的openid
	 * @return string
	 */
	public function openid() {
		$data = $this->Token;
		if (isset ( $data ['openid'] ))
			return $data ['openid'];
		else
			throw new Exception ( '没有获取到微信用户ID！' );
	}
	/**
	 * 获取当前授权应用的openid
	 * @return string
	 */
	public function token() {
		$data = $this->Token;
		if (isset ( $data ['access_token'] ))
			return $data ['access_token'];
		else
			throw new Exception ( '没有获取到微信用户access_token！' );
	}
	
	/**
	 * 请求code 
	 */
	public function getRequestCodeURL(){
		//Oauth 标准参数
		$params = array(
			'appid'     => $this->AppKey,
			'redirect_uri'  => $this->Callback,
			'response_type' => $this->ResponseType,
		);
		
		//获取额外参数
		if($this->Authorize){
			parse_str($this->Authorize, $_param);
			if(is_array($_param)){
				$params = array_merge($params, $_param);
			} else {
				throw new Exception('AUTHORIZE配置不正确！');
			}
		}
		return $this->GetRequestCodeURL . '?' . http_build_query($params);
	}
	
	/**
	 * 获取access_token
	 * @param string $code 上一步请求到的code
	 */
	public function getAccessToken($code, $extend = null){
		$params = array(
				'appid'     => $this->AppKey,
				'secret' => $this->AppSecret,
				'grant_type'    => $this->GrantType,
				'code'          => $code,
				'redirect_uri'  => $this->Callback,
		);

		$data = $this->http($this->GetAccessTokenURL, $params, 'POST');
		$this->Token = $this->parseToken($data, $extend);
		return $this->Token;
	}
	
	//登录成功，获取用户信息
	public function wx($token) {
		
		$data = $this->call ( 'sns/userinfo', "openid={$this->openid()}&lang=zh_CN&access_token=".$this->token() );
		if ($data ['error_code'] == 0) {
			$userInfo ['type'] = 'WX';
			$userInfo ['name'] = $data ['nickname'];
			$userInfo ['nick'] = $data ['nickname'];
			$userInfo ['head'] = $data ['headimgurl'];
			return $userInfo;
		} else {
			return false;
			//throw_exception ( "获取新浪微博用户信息失败：{$data['error']}" );
		}
	
	}
}