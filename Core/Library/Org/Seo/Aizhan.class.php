<?php
namespace Org\Seo;
class Aizhan {
	
	protected $baseURL = 'http://www.aizhan.com/cha';
	
	protected $htmlContent = "";
	
	protected $url = "";
	
	/**
	 * 构造方法，配置应用信息
	 * @param array $token 
	 */
	public function __construct($url = null) {
		$this->url = $url;
		if (empty ( $this->url )) {
			throw_exception ( '请配置您申请的URL' );
		}
		set_time_limit ( 0 );
		$this->content ();
	}
	
	/**
	 * +---------------------------------------
	 * 远程抓取URL内容
	 * +---------------------------------------
	 * @param string	 $url
	 */
	private function curl($url) {
		$host = parse_url ( $url, PHP_URL_HOST );
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2)' );
		curl_setopt ( $ch, CURLOPT_REFERER, $host );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 15 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 2 );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
		$str = curl_exec ( $ch );
		curl_close ( $ch );
		return $str;
	}
	
	/**
	 * +---------------------------------------
	 * 判断url是否有效
	 * +---------------------------------------
	 * @param string	 $url
	 */
	private function valid_host($url) {
		$url = str_replace ( array ('https://', 'http://' ), '', $url );
		$url = trim ( $url, '/' );
		if (preg_match ( "/^([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i", $url )) {
			return $url;
		}
		return false;
	}
	/**
	 * +---------------------------------------
	 * 远程获取url内容
	 * +---------------------------------------
	 * @param string	 $url
	 */
	private function content() {
		$url = $this->valid_host ( $this->url );
		if (! $url) {
			return false;
		}
		$this->url = $url;
		$url = $this->baseURL . "/" . $this->url;
		$this->htmlContent = $this->curl ( $url );
		if (empty ( $this->htmlContent )) {
			return false;
		}
	}
	
	/**
	 * 获取seo的数据
	 * Enter description here ...
	 */
	public function seo() {
		if (empty ( $this->htmlContent )) {
			return array ('status' => 0, "info" => "抓取数据失败", "data" => "" );
		}
		$this->htmlContent = str_replace ( "\n", "", $this->htmlContent );
		$data = array ("pr" => 0, "br" => "0", "position" => "-", "baidu_shoulu" => "0", "google_shoulu" => 0,"keyword"=>"" );
		
		//获取谷歌pr
		preg_match ( '/<span id="main_pr">(.*)<\/span>/U', $this->htmlContent, $googleConent ); //pr
		if ($googleConent && isset ( $googleConent [1] )) {
			preg_match ( '/<img.*?src=\s*?"?([^"\s]+)(?!\/>)"?\s*?/is', $googleConent [1], $google_pr );
			if ($google_pr && isset ( $google_pr [1] )) {
				$filename = basename ( $google_pr [1], ".gif" );
				$data ['pr'] = str_replace ( "pr", "", $filename );
				$data ['pr']=is_numeric($data ['pr'] ) ? intval($data ['pr'] ) : 0;
			}
		}
		
		//获取百度权重
		preg_match ( '#<a.+?href="(.+?)" target="_blank" id="baidu_rank">(.+?)</a>#', $this->htmlContent, $baiduContent );
		if ($baiduContent && isset ( $baiduContent [2] )) {
			preg_match ( '/<img.*?src=\s*?"?([^"\s]+)(?!\/>)"?\s*?/is', $baiduContent [2], $baidu_br );
			$data ['br'] = $baidu_br && isset ( $baidu_br [1] ) ? basename ( $baidu_br [1], ".gif" ) : "0";
			$data ['br'] = is_numeric($data ['br'] ) ? intval($data ['br'] ) : 0;
		}
		
		//百度位置
		preg_match ( '/<span id="baidu_date_pos">(.*)<\/span>/U', $this->htmlContent, $baidu_ps ); //pr
		if ($baidu_ps && isset ( $baidu_ps [1] )) {
			preg_match ( '#<a.+?href="(.+?)">(.+?)</a>#', $baidu_ps [1], $position );
			$data ['position'] = $position && isset ( $position [2] ) ? $position [2] : "-";
		}
		
		//百度收录
		preg_match ( '/<td id="baidu">(.*)<\/td>/U', $this->htmlContent, $baidu_shoulu );
		if ($baidu_shoulu && isset ( $baidu_shoulu [1] )) {
			preg_match ( '#<a.+?href="(.+?)">(.+?)</a>#', $baidu_shoulu [1], $baidu_sl );
			if ($baidu_sl && isset ( $baidu_sl [2] )) {
				$data ['baidu_shoulu'] = $baidu_sl [2];
			}
		}
		//谷歌收录
		preg_match ( '/<td id="google">(.*)<\/td>/U', $this->htmlContent, $google_showlu );
		if ($google_showlu && isset ( $google_showlu [1] )) {
			preg_match ( '#<a.+?href="(.+?)">(.+?)</a>#', $google_showlu [1], $google_sl );
			if ($google_sl && isset ( $google_sl [2] )) {
				$data ['google_shoulu'] = $google_sl [2];
			}
		}
		
		//获取关键词
		preg_match('/<td id="webpage_keywords">(.*)<\/td>/U',$this->htmlContent,$keyword);
		if ($keyword&&$keyword[1]){
			$data['keyword']=$keyword[1];
		}	
		unset ( $this->htmlContent );
		return array ('status' => 1, "info" => "抓取数据成功", "data" => $data );
	}

}