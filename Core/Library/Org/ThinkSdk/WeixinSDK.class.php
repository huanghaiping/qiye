<?php
// +----------------------------------------------------------------------
// | 微信扫码登录接口 [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Alan <451648237@qq.com> <http://www.zhansu.cn>
// +----------------------------------------------------------------------
// | WeixinSDK.class.php 2013-12-24
// +----------------------------------------------------------------------

use Org\ThinkSdk\ThinkOauth;
class WeixinSDK extends ThinkOauth{
	
	 /**
     * 获取requestCode的api接口
     * @var string
     */
    protected $GetRequestCodeURL = 'https://open.weixin.qq.com/connect/qrconnect';

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
     * 组装接口调用参数 并调用接口
     * @param  string $api 微博API
     * @param  string $param 调用API的额外参数
     * @param  string $method HTTP请求方法 默认为GET
     * @return json
     */
    public function call($api, $param = '', $method = 'GET', $multi = false)
    {
        /* 腾讯微博调用公共参数 */
        $params = array(
            'access_token' => $this->Token['access_token'],
            'openid' => $this->openid(),
        );
        $vars = $this->param($params, $param);
        $data = $this->http($this->url($api), $vars, $method, array(), $multi);
        return json_decode($data, true);
    }
    
	/**
	 * 获取访问code
	 * @see Org\ThinkSdk.ThinkOauth::getRequestCodeURL()
	 */
    public function getRequestCodeURL()
    {
        $params = array(
            'appid' => $this->AppKey,
            'redirect_uri' => $this->Callback,
            'response_type' => 'code',
            'scope' => 'snsapi_login'
        );
        return $this->GetRequestCodeURL . '?' . http_build_query($params);
    }

    /**
     * 获取access_token
     * @param string $code 上一步请求到的code
     */
    public function getAccessToken($code, $extend = null)
    {
        $params = array(
            'appid' => $this->AppKey,
            'secret' => $this->AppSecret,
            'grant_type' => $this->GrantType,
            'code' => $code,
        );
        $data = $this->http($this->GetAccessTokenURL, $params, 'POST');
        $this->Token = $this->parseToken($data, $extend);
        return $this->Token;
    }
    
  /**
     * 解析access_token方法请求后的返回值
     */
    protected function parseToken($result, $extend)
    {
        $data = json_decode($result, true);
        //parse_str($result, $data);
        if ($data['access_token'] && $data['expires_in']) {
            $this->Token = $data;
            $data['openid'] = $this->openid();
            return $data;
        } else
            throw new Exception("获取微信 ACCESS_TOKEN 出错：{$result}");
    }

    /**
     * 获取当前授权应用的openid
     */
    public function openid()
    {
        $data = $this->Token;
        if (!empty($data['openid']))
            return $data['openid'];
        else
            exit('没有获取到微信用户ID！');
    }
    
//登录成功，获取新浪微博用户信息
	public function weixin($token) {
		
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