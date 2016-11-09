<?php
namespace Home\Controller;
class UserController extends CommonController {
	
	protected $user_model, $uid;
	
	/**
	 * +----------------------------------------------------------
	 * 初始化会员中心模块
	 * +----------------------------------------------------------
	 * @see Home\Controller.CommonController::_initialize()
	 */
	public function _initialize() {
		parent::_initialize ();
		if (empty ( $this->userInfo )) {
			redirect ( U ( 'login/index' ) );
		}
		$this->user_model = D ( "User" );
		$this->uid = $this->userInfo ['uid'];
	}
	
	/**
	 * +----------------------------------------------------------
	 * 个人会员中心主页
	 * +----------------------------------------------------------
	 */
	public function index() {
		$userInfo = $this->user_model->getUserDetailByUid ( $this->uid ); //获取用户信息
		if (! $userInfo) {
			redirect ( U ( 'Login/index' ) );
		}
		$userInfo ['constellation'] = isset ( $userInfo ['constellation'] ) && ! empty ( $userInfo ['constellation'] ) ? $this->user_model->getConstellation ( $userInfo ['constellation'] ) : "";
		$this->assign ( "userDetail", $userInfo );
		
		$user_group_model = D ( "UserGroup" ); //获取用户等级
		$group_info = $user_group_model->getGroupByGroupId ( $userInfo ['group_id'] );
		$this->assign ( "group_info", $group_info );
		
		$seoInfo = array ('site_title' => L('MEMBER_CENTER'));
		$this->get_seo_info ( $seoInfo );
		$this->display ();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 订单页面
	 * +----------------------------------------------------------
	 */
	public function order() {
		
		$where = " user_id='" . $this->uid . "' and order_status!=2 and lang='".$this->lang."'  ";
		$sn=isset($_REQUEST['sn']) ? addSlashesFun($_REQUEST['sn']) : "";
		if (!empty($sn)){
			$where.=" and order_sn='".$sn."'";
		}
		$order_model = D ( "order" );
		$count = $order_model->where ( $where )->count ();
		if ($count) {
			$listRows = C ( 'PAGE_LISTROWS' );
			$page = new \Think\Page ( $count, $listRows );
			
			$page->setConfig ( 'theme', '%FIRST% %UP_PAGE% %LINK_PAGE%  %DOWN_PAGE% %END%' );
			$pages = $page->fshow ();
			$list = $order_model->where ( $where )->order ( 'order_id desc' )->limit ( $page->firstRow . ',' . $page->listRows )->select ();
			foreach ( $list as $key => $value ) {
				$value ['product'] = $order_model->getOrderGoods ( $value ['order_id'] );
				$list [$key] = $value;
			}
			$this->assign ( 'pages', $pages );
			$this->assign ( 'list', $list );
		}
		$seoInfo = array ('site_title' => L('ORDER_LIST') );
		$this->get_seo_info ( $seoInfo );
		$this->display ();
	
	}
	/**
	 * +----------------------------------------------------------
	 * 订单详细页面
	 * +----------------------------------------------------------
	 */
	public function order_show(){
		$order_id=isset($_GET['order_id']) ? intval($_GET['order_id']) : "";
		if (empty($order_id)){
			$this->error(L('ILLEGAL_REQUEST'));
		}
		$order_model = D ( "order" );
		$order_info = $order_model->where ( "order_id='" . $order_id . "' and user_id='".$this->uid."' " )->limit ( 1 )->find ();
		if (empty($order_info)){
			$this->error(L('NO_ORDER'));
		}
		//获取商品信息
		$shop_list=$order_model->getOrderGoods($order_id);
		$this->assign("shop_list",$shop_list);
		$this->assign("order_info",$order_info);
		$seoInfo = array ('site_title' => L('ORDER_DETAILS') );
		$this->get_seo_info ( $seoInfo );
		$this->display();
	}
	
	/**
	 * +----------------------------------------------------------
	 * 修改密码
	 * +----------------------------------------------------------
	 */
	public function password() {
		if (IS_POST) {
			$data = array ();
			$data ['oldpassword'] = isset ( $_POST ['oldpassword'] ) && ! empty ( $_POST ['oldpassword'] ) ? md5 ( md5 ( $_POST ['oldpassword'] ) ) : "";
			if (empty ( $data ['oldpassword'] )) {
				$this->error ( L('PLEASE_INPUT_A_PASSWORD') );
			}
			$data ['password'] = isset ( $_POST ['password'] ) && ! empty ( $_POST ['password'] ) ? md5 ( md5 ( $_POST ['password'] ) ) : "";
			if (empty ( $data ['oldpassword'] )) {
				$this->error ( L('PLEASE_INPUT_A_PASSWORD') );
			}
			$data ['repassword'] = isset ( $_POST ['repassword'] ) && ! empty ( $_POST ['repassword'] ) ? md5 ( md5 ( $_POST ['repassword'] ) ) : "";
			if ($data ['password'] != $data ['repassword']) {
				$this->error ( L('CONFIRM_PASSWORD_ERROR') );
			}
			if ($data ['password'] == $data ['oldpassword']) {
				$this->error ( L('OLD_PASSWORD_ NEW_PASSWORD') );
			}
			$verify = isset ( $_POST ['verify'] ) ? addSlashesFun ( $_POST ['verify'] ) : "";
			if (empty ( $verify )) {
				$this->error ( L('VERIFICATION_CODE_IS_EMPTY') );
			}
			$Verify_model = new \Think\Verify ();
			if (! $Verify_model->check ( $verify )) {
				$this->error ( L('VERIFICATION_CODE_ERROR') );
			}
			
			$user_info = $this->user_model->where ( "uid='" . $this->uid . "'" )->field ( "password" )->find ();
			if ($user_info ['password'] != $data ['oldpassword']) {
				$this->error ( L('PASSWORD_ERROR'));
			}
			$result = $this->user_model->where ( "uid='" . $this->uid . "'" )->setField ( "password", $data ['password'] );
			if ($result) {
				S ( "ui_" . $this->uid, null );
				$this->success ( L('SUCCESSFUL_MODIFICATION'), U ( 'index' ) );
			} else {
				$this->error ( L('MODIFY_FAILED') );
			}
		
		} else {
			$seoInfo = array ('site_title' => L('MODIFY_PASSWORD') );
			$this->get_seo_info ( $seoInfo );
			$this->display ();
		}
	}
	
	/**
	 * +----------------------------------------------------------
	 * 个人的资料页
	 * +----------------------------------------------------------
	 */
	public function info() {
		if (IS_POST) {
			$uid = isset ( $_POST ['uid'] ) ? intval ( $_POST ['uid'] ) : "";
			if ($this->userInfo ['uid'] != $uid || empty ( $uid )) {
				$this->error ( L('ILLEGAL_REQUEST'), U ( 'User/info' ) );
			}
			import ( "Org.Util.Input" );
			$Areas = D ( 'Area' );
			$user_info_model = D ( "UserInfo" );
			$data = array ();
			$data ['nickname'] = isset ( $_POST ['nickname'] ) ? \Input::getVar ( I ( 'post.nickname' ) ) : "";
			$data ['email'] = isset ( $_POST ['email'] ) ? \Input::getVar ( I ( 'post.email' ) ) : "";
			$data ['mobile'] = isset ( $_POST ['mobile'] ) ? \Input::getVar ( I ( 'post.mobile' ) ) : "";
			if ($data ['nickname'] != $_POST ['username']) {
				$_result = $this->user_model->checkUidByField ( 'nickname', $data ['nickname'] );
				if ($_result) {
					$this->error ( L('USER_NAME_ALREADY_EXISTS'), U ( 'User/info' ) );
				}
			}
			if ($data ['email'] != $_POST ['old_email']) {
				if (! checkEmailFormat ( $data ['email'] )) {
					$this->error ( L('MAILBOX_FORMAT_ERROR'), U ( 'User/info' ) );
				}
				$_result = $this->user_model->checkUidByField ( 'email', $data ['email'] );
				if ($_result) {
					$this->error ( L('MAILBOX_ALREADY_EXISTS'), U ( 'User/info' ) );
				}
			}
			if ($data ['mobile'] != $_POST ['old_mobile']) {
				if (! valdeTel ( $data ['mobile'] )) {
					$this->error ( L("CELL_PHONE_NUMBER_FORMAT_ERROR"), U ( 'User/info' ) );
				}
				$_result = $this->user_model->checkUidByField ( 'mobile', $data ['mobile'] );
				if ($_result) {
					$this->error ( L('CELL_PHONE_NUMBER_ALREADY_EXISTS'), U ( 'User/info' ) );
				}
			}
			$update_user = $this->user_model->where ( "uid='{$uid}'" )->save ( $data );
			$data = array ();
			$data ['province'] = isset ( $_POST ['province'] ) ? intval ( $_POST ['province'] ) : 0;
			$data ['city'] = isset ( $_POST ['city'] ) ? intval ( $_POST ['city'] ) : 0;
			$data ['area'] = isset ( $_POST ['area'] ) ? intval ( $_POST ['area'] ) : 0;
			$data ['constellation'] = isset ( $_POST ['constellation'] ) ? intval ( $_POST ['constellation'] ) : 0;
			$area_info = $Areas->getAreaById ( array ($data ['province'], $data ['city'], $data ['area'] ) );
			$data ['loation'] = implode ( ",", getSubByKey ( $area_info, "area_name" ) );
			$data ['description'] = isset ( $_POST ['description'] ) ? \Input::getVar ( I ( 'post.description' ) ) : "";
			$data ['truename'] = isset ( $_POST ['truename'] ) ? \Input::getVar ( I ( 'post.truename' ) ) : "";
			$data ['sex'] = isset ( $_POST ['sex'] ) ? intval ( $_POST ['sex'] ) : 0;
			$data ['update_time'] = time ();
			$update_user_info = $user_info_model->where ( "mid='{$uid}'" )->save ( $data );
			if ($update_user || $update_user_info) {
				S ( "ui_" . $uid, null );
				$this->success ( L('SUCCESSFUL_MODIFICATION'), U ( 'User/index' ) );
			} else {
				$this->error (L('MODIFY_FAILED') );
			}
		} else {
			//获取用户的所有信息
			$user_info = $this->user_model->getUserDetailByUid ( $this->uid );
			$this->assign ( "userDetail", $user_info );
			$Areas = D ( 'Area' );
			$province = $Areas->getAreaList ( 1 );
			if (! empty ( $user_info ['province'] )) {
				$city = $Areas->getAreaList ( $user_info ['province'] );
				$this->assign ( "city", $city );
			}
			if (! empty ( $user_info ['city'] )) {
				$area = $Areas->getAreaList ( $user_info ['city'] );
				$this->assign ( "area", $area );
			}
			$this->assign ( "constellation", $this->user_model->getConstellation () );
			$this->assign ( 'province', $province );
			$seoInfo = array ('site_title' => L('MODIFY_PERSONAL_INFORMATION') );
			$this->get_seo_info ( $seoInfo );
			$this->display ();
		}
	}

}