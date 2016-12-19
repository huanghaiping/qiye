<?php
namespace Jzadmin\Model;
/**
 * +-------------------------------------------
 * 广告的模型类
 * +-------------------------------------------
 * @author Alan
 */
class LinkModel extends CommonModel {
	protected $_validate = array (array ('name', 'require', '网站名称不能为空', 1 ) );
	
	/**
	 * +-----------------------------------------
	 * 验证友情链接模型字段
	 * +-----------------------------------------
	 * @param Array $postData	POST提交上来的数据
	 * 
	 * @return Array 返回过滤后的数组
	 */
	public function createData($postData) {
		$data = array ();
		$data ['createtime'] = time ();
		$data ['name'] = isset ( $postData ['name'] ) ? addSlashesFun ( $postData ['name'] ) : "";
		$data ['siteurl'] = isset ( $postData ['siteurl'] ) ? addSlashesFun ( $postData ['siteurl'] ) : "";
		$data ['linktype'] = isset ( $postData ['linktype'] ) ? intval ( $postData ['linktype'] ) : "";
		$data ['status'] = isset ( $postData ['status'] ) ? intval ( $postData ['status'] ) : "";
		$data ['listorder'] = isset ( $postData ['listorder'] ) ? intval ( $postData ['listorder'] ) : "";
		$data ['lang'] = $this->lang;
		$file_data = $this->uploadImg ( array ('logo' ), 'ad' );
		if (! empty ( $file_data ['logo'] )) {
			$data ['logo'] = $file_data ['logo'];
		} else {
			$data ['logo'] = "";
		}
		return $data;
	}
	
	/**
	 * 发送HTTP请求方法，目前只支持CURL发送请求
	 * @param  string $url    请求URL
	 * @param  array  $params 请求参数
	 * @param  string $method 请求方法GET/POST
	 * @return array  $data   响应数据
	 */
	public function http($url, $params, $method = 'POST', $header = array(), $multi = false) {
		$opts = array (CURLOPT_TIMEOUT => 30, CURLOPT_RETURNTRANSFER => 1, CURLOPT_SSL_VERIFYPEER => false, CURLOPT_SSL_VERIFYHOST => false, CURLOPT_HTTPHEADER => $header );
		
		/* 根据请求类型设置特定参数 */
		switch (strtoupper ( $method )) {
			case 'GET' :
				$opts [CURLOPT_URL] = $url . '?' . http_build_query ( $params );
				break;
			case 'POST' :
				//判断是否传输文件
				$params = $multi ? $params : http_build_query ( $params );
				$opts [CURLOPT_URL] = $url;
				$opts [CURLOPT_POST] = 1;
				$opts [CURLOPT_POSTFIELDS] = $params;
				break;
			default :
				E ( '不支持的请求方式！' );
		}
		/* 初始化并执行curl请求 */
		$ch = curl_init ();
		curl_setopt_array ( $ch, $opts );
		$data = curl_exec ( $ch );
		$error = curl_error ( $ch );
		curl_close ( $ch );
		if ($error)
			E ( '请求发生错误：' . $error );
		return $data;
	}
	
	/**
	 * 将json转换为数组
	 * Enter description here ...
	 * @param unknown_type $web json字符串
	 */
	public function json_to_array($web) {
		$arr = array ();
		foreach ( $web as $k => $w ) {
			if (is_object ( $w ))
				$arr [$k] = $this->json_to_array ( $w ); //判断类型是不是object
			else
				$arr [$k] = $w;
		}
		return $arr;
	}
	
	/**
	 * 判断链接是否可用
	 * 
	 */
	public function getHostStatus($url) {
		if (empty ( $url ))
			return false;
		$array = get_headers ( $url, 1 );
		if ($array && isset ( $array [0] )) {
			if (preg_match ( '/200/', $array [0] )) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	/**
	 * 根据类型获取请求接口
	 * 
	 * @param int	 $typeid
	 */
	public function getUrl($typeid) {
		$hoturl = C ( "ADMIN_SITE_URL" );
		$url = "";
		switch ($typeid) {
			case 1 :
				$url = "/Api/linkType";
				break; //获取链接分类
			case 2 :
				$url = "/Api/addLink";
				break; //添加/修改链接
			case 3 :
				$url = "/Api/getLinkById";
				break; //获取单个链接信息
			case 4 :
				$url = "/Api/updateStatus";
				break; //更改链接状态
			case 5 :
				$url = "/Api/getlink";
				break; //获取推广的链接
			case 6 :
				$url ="/Api/authorize";
				break;//商业授权检测
			case 7 :
				$url ="/Api/upgrade";
				break;//系统升级
		}
		$request_url = $hoturl . $url;
		$header_status = $this->getHostStatus ( $request_url );
		if ($header_status) {
			return $request_url;
		} else {
			return false;
		}
	}
	/**
	 * 获取链接的分类
	 * 
	 * @param int	 $typeid
	 */
	public function category($typeid = "", $category_url = "") {
		$category_list = F ( "LinkTypeList" );
		if (! $category_list) {
			$category_url = empty ( $category_url ) ? $this->getUrl ( 1 ) : $category_url;
			$category = $this->http ( $category_url, array (), "POST" );
			if ($category) {
				$category_list = $this->json_to_array ( json_decode ( $category ) );
				F ( "LinkTypeList", $category_list );
			} else {
				return false;
			}
		}
		return empty ( $typeid ) ? $category_list : $category_list [$typeid];
	}
	
	/**
	 * 对数组排序
	 * @param $para 排序前的数组
	 * return 排序后的数组
	 */
	protected function argSort($para) {
		ksort ( $para );
		reset ( $para );
		return $para;
	}
	
	/**
	 * 签名字符串
	 * @param $prestr 需要签名的字符串
	 * @param $key 私钥
	 * return 签名结果
	 */
	protected function md5Sign($prestr, $key) {
		$prestr = $prestr . $key;
		return md5 ( $prestr );
	}
	
	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param $para 需要拼接的数组
	 * return 拼接完成以后的字符串
	 */
	protected function createLinkstring($para) {
		$arg = "";
		while ( list ( $key, $val ) = each ( $para ) ) {
			$arg .= $key . "=" . $val . "&";
		}
		//去掉最后一个&字符
		$arg = substr ( $arg, 0, count ( $arg ) - 2 );
		
		//如果存在转义字符，那么去掉转义
		if (get_magic_quotes_gpc ()) {
			$arg = stripslashes ( $arg );
		}
		
		return $arg;
	}
	
	/**
	 * 除去数组中的空值和签名参数
	 * @param $para 签名参数组
	 * return 去掉空值与签名参数后的新签名参数组
	 */
	protected function paraFilter($para) {
		$para_filter = array ();
		while ( list ( $key, $val ) = each ( $para ) ) {
			if ($key == "sign" || $key == "sign_type" || $val == "" || $key == "_URL_")
				continue;
			else
				$para_filter [$key] = $para [$key];
		}
		return $para_filter;
	}
	
	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	public function buildRequestMysign($data) {
		//除去待签名参数数组中的空值和签名参数$data
		$para_filter = $this->paraFilter ( $data );
		//对待签名参数数组排序
		$para_sort = $this->argSort ( $para_filter );
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring ( $para_sort );
		$mysign = $this->md5Sign ( $prestr, "c6e1dad9089d8ed6efcc8c518505c90c" );
		return $mysign;
	}
	
	/**
	 * 获取同类型的所有友链
	 */
	public function getLink($param) {
		//获取所有链接
		$link_list = S ( "link_list" );
		if (! $link_list) {
			$service_info_url = $this->getUrl ( 5 ); //获取链接的URL
			$link_list_string = $this->http ( $service_info_url, $param ); //更改服务器状态
			$link_list_data = $this->json_to_array ( json_decode ( $link_list_string ) );
			if ($link_list_data) {
				$link_list=$link_list_data['data'];
				S ( "link_list", $link_list , array ('expire' => 43200 ) );
			}
		}
		return $link_list;
	}
}