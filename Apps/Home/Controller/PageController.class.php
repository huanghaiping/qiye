<?php
namespace Home\Controller;
class PageController extends CommonController {
	
	/**
	 * 单页模型的详细页
	 * Enter description here ...
	 */
	public function index($catid = '') {
		if (isset ( $_GET ['id'] ))
			$catid = intval ( $_GET ['id'] );
		if ($catid) {
			if (isset ( $this->categorys [$catid] ['parent_list'] ) && ! empty ( $this->categorys [$catid] ['parent_list'] )) {
				$topid_info = array_values ( $this->categorys [$catid] ['parent_list'] );
				$topid = $topid_info [0] ['id'];
			} else {
				$topid = intval ( $catid );
			}
			$catInfo = $this->categorys [$catid];
			$module = $catInfo ['model_name'];
			$this->assign ( "catInfo", $catInfo );
			$this->assign ( 'catid', $catid );
			$this->assign ( 'topid', $topid );
		
		} else {
			if (! isset ( $this->categorys [$catid] )) {
				E ( '非法操作', '404' );
			}
		}
		$model = D ( "Page" );
		$data = $model->where ( "catid='" . $catid . "'" )->find ();
		$seoInfo = array ('site_title' => $data ['title'], 'site_keyword' => $data ['keyword'], 'site_description' => $data ['description'] );
		$this->get_seo_info ( $seoInfo );
		if (! empty ( $data ['template'] )) {
			$template = $data ['template'];
		} else {
			$template = empty ( $catInfo ['template_list'] ) ? $module . ":index" : $catInfo ['template_list'];
		}
		$this->assign ( $data );
		$this->display ( $template );
	}
	
	public function lists($catid = '') {
		$this->index ( $catid );
	}
}