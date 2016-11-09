<?php
namespace Org\Pay;
abstract class ThinkPay {
	
	/**
	 * 支付方式的支付的APPID,例如：支付宝合作身份者id,以2088开头的16位纯数字
	 * @var string
	 */
	protected $APPID = '';
	
	/**
	 * 支付的安全检验码，以数字和字母组成的32位字符，例如：公众帐号secert
	 * @var string
	 */
	protected $APPSECRET = '';
	
	/**
	 * 支付的账号
	 * @var string
	 */
	protected $MCHID = '';
	
	/**
	 * 支付的商户支付密钥
	 * @var string
	 */
	protected $KEY = '';
	
	/**
	 * 调用接口类型,比如是支付宝，微信
	 * @var string
	 */
	private $Type = '';
	
	/**
	 * 调用接口类型
	 * @var string
	 */
	private $Token = '';
	
	/**
	 * 支付证书路径
	 * @var string
	 */
	private $SSLCERT_PATH='';
	
	/**
	 * 支付证书密钥路径
	 * @var string
	 */
	private $SSLKEY_PATH='';
	
	/**
	 * 构造方法，配置应用信息
	 * @param array $token 
	 */
	public function __construct($token = null) {
		//设置SDK类型
		$class = get_class ( $this );
		$this->Type = (substr ( $class, 0, strlen ( $class ) - 3 ));
		
		//获取应用配置
		$pay_info = D ( "PayApi" )->getPayList ( $this->Type );
		if (! $pay_info) {
			throw_exception ( L("PLEASE_CONFIGURE_PARTNER_ KEY") );
		} else {
			$config = isset ( $pay_info ['config'] ) ? $pay_info ['config'] : array ();
			$this->APPID = isset ( $config ['APPID'] ) ? $config ['APPID'] : "";
			$this->APPSECRET = isset ( $config ['APPSECRET'] ) ? $config ['APPSECRET'] : "";
			$this->MCHID = isset ( $config ['MCHID'] ) ? $config ['MCHID'] : "";
			$this->KEY = isset ( $config ['KEY'] ) ? $config ['KEY'] : "";
			$this->SSLCERT_PATH = isset ( $config ['SSLKEY_PATH'] ) ? $config ['SSLKEY_PATH'] : "";
			$this->SSLKEY_PATH = isset ( $config ['SSLKEY_PATH'] ) ? $config ['SSLKEY_PATH'] : "";
			$this->Token = $token; //设置获取到的TOKEN
		}
	}
	
	/**
	 * 取得Oauth实例
	 * @static
	 * @return mixed 返回Oauth
	 */
	public static function getInstance($type) {
		$name = ucfirst ( strtolower ( $type ) ) . 'SDK';
		$filename = LIB_PATH . "Org/Pay/{$name}.class.php";
		if (file_exists ( $filename )) {
			import ( "Org.Pay." . $name );
			if (class_exists ( $name )) {
				return new $name ();
			} else {
				return false; //L ( '_CLASS_NOT_EXIST_' ) . ':' . $name 
			}
		} else {
			return false;
		}
	}
}