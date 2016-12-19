<?php
namespace Home\Controller;
class FeedbackController extends CommonController {
	
	/**
	 * 意见反馈页面
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
		$Field_model = D ( "Field" ); 
		$field_list = $Field_model->getFieldByModuelId ( $catInfo ['typeid'] );
		$this->assign ( "field_list", $field_list );
		$form = new \Org\Util\Form ();
		$this->assign ( "form", $form );
		if ($field_list) {
			$field_type = getSubByKey ( $field_list, "type" );
			$this->assign ( "field_type", $field_type );
		}
		$site_title = empty ( $catInfo ['site_title'] ) ? $catInfo ['title'] : $catInfo ['site_title'];
		$seoInfo = array ('site_title' => $site_title, 'site_keyword' => $catInfo ['site_keyword'], 'site_description' => $catInfo ['site_description'] );
		$this->get_seo_info ( $seoInfo );
		$template = empty ( $catInfo ['template_list'] ) ? $module . ":index" : $catInfo ['template_list'];
		$this->display ( $template );
	}
	
	/**
	 * 保留意见反馈的信息
	 * 
	 */
	public function saves() {
		$catid = isset ( $_POST ['catid'] ) ? intval ( $_POST ['catid'] ) : "";
		if (empty ( $catid ) || ! isset ( $this->categorys [$catid] )) {
			E ( '非法操作', '404' );
		}
		$fields_verify = array ();
		$catInfo = $this->categorys [$catid];
		$Field_model = D ( "Field" );
		$field_list = $Field_model->getFieldByModuelId ( $catInfo ['typeid'] );
		if (empty ( $field_list )) {
			E ( '非法操作', '404' );
		}
		$model_name = D ( $catInfo ['model_name'] );
		$_POST = $Field_model->checkfield ( $field_list, $_POST );
		$_POST ['lang'] = $this->lang;
		foreach ( $field_list as $value ) {
			if ($value ['type'] == 'verify') {
				$fields_verify = $value;
				break;
			}
		}
		if (! empty ( $fields_verify ) && ! A ( "Verify" )->checkverify ( $_POST [$fields_verify ['field']] )) {
			$this->error ( "验证码错误" );
		}
		$rules = $Field_model->validate ( $field_list );
		$createData = $model_name->validate ( $rules )->create ( $_POST );
		if ($createData) {
			$result = $model_name->add ( $createData );
			if ($result) {
				$this->success ( "反馈成功", U ( 'index', array ('id' => $catid ) ) );
			} else {
				$this->error ( $model_name->getError () );
			}
		} else {
			$this->error ( $model_name->getError () );
		}
	}
}