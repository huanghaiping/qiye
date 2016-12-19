<?php
namespace Jzadmin\Controller;
class SitemapController extends CommonController {
	
	/**
	 * +----------------------------------------------
	 * 生成百度,谷歌SiteMap
	 * +----------------------------------------------
	 */
	public function index() {
		$menu_model = D ( "Menu" );
		$temp = $menu_model->getCategory ( array ("lang" => $this->lang ) );
		$row=array();
		if ($temp) {
			$module_model_name = D ( "Module" );
			$moduleIds = $module_model_name->getModuleIdByModuleIds ( getSubByKey ( $temp, "typeid" ) );
			foreach ( $temp as $key => $value ) {
				if ($value['typeid']!=0){
					$value ['module_name'] = array_key_exists ( $value ['typeid'], $moduleIds ) ? $moduleIds [$value ['typeid']] : array ();
					$value ['url'] = createHomeUrl ( $value );
					$temp [$key] = $value;
					$row[$value['id']]=$value;
				}else{
					unset($temp [$key]);
				}
			}
		}
		if (IS_POST) {
			if (empty ( $_POST ['catid'] )) {
				$this->error ( "请选择数据来源" );
			}
			$map ['catid'] = array ('in', $_POST ['catid'] );
			$map ['status'] = array ('eq', 0 );
			$catid_array = is_array ( $_POST ['catid'] ) ? $_POST ['catid'] : array ($_POST ['catid'] );
			$type = isset ( $_POST ['sitemaptype'] ) ? intval ( $_POST ['sitemaptype'] ) : 0;
			$sitemapstr = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n";
			switch ($type) {
				case 0 :
					$sitemapstr .= "<urlset>\r\n";
					break;
				case 1 :
					$sitemapstr .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
					break;
			
			}
			foreach ( $temp as $key => $value ) {
				if (in_array ( $value ['id'], $catid_array )) {
					
					$sitemapstr.=$this->urltemplate($value['url'], $_POST ['changefreq'], $_POST ['priority']);
					$result = D ( $value ['model_name'] )->where ( $map )->order ( 'id desc' )->select ();
					if ($result) {
						foreach ( $result as $v ) {
							$param=isset($v['jump_url'])&&!empty($v['jump_url']) ? array('jump_url'=>$v['jump_url']):array();
							$url= createHomeUrl ( $row[$v['catid']],$v['id'] ,$param);
							$sitemapstr.=$this->urltemplate($url, $_POST ['changefreq'], $_POST ['priority']);
						}
					}
				}
			}
			
			$sitemapstr .= "</urlset>";
			file_put_contents ("./sitemap.xml", $sitemapstr );
			$this->success ( "sitemap在线生成完成,生成在根目录" );
		} else {
			$this->assign ( "list", $temp );
			$this->display ( "Index:sitemap" );
		}
	}
	/**
	 * 获取sitemap中的url模板
	 * Enter description here ...
	 */
	protected  function urltemplate($url, $changefreq, $priority) {
		$sitemapstr = "";
		$sitemapstr .= "<url>\r\n";
		$sitemapstr .= "<loc>http://".$_SERVER["HTTP_HOST"] . $url . "</loc>\r\n";
		$sitemapstr .= "<lastmod>" . date ( 'Y-m-d', NOW_TIME ) . "</lastmod>\r\n";
		$sitemapstr .= "<changefreq>" . $changefreq . "</changefreq>\r\n";
		$sitemapstr .= "<priority>" . $priority . "</priority>\r\n";
		$sitemapstr .= "</url>\r\n\r\n";
		return $sitemapstr;
	}
}