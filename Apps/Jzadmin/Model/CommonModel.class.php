<?php
namespace Jzadmin\Model;
use Think\Model;

class CommonModel extends Model {
	
	public $lang = LANG_SET; //设置语言标识
	/**
	 * 默认的分页设置，放在BASE类里，避免每次要分页都需要重新写分页的设置代码
	 * @author			Nicky
	 *
	 * @access			protected		
	 * @param			string	 		$condition				查询的条件	
	 * @param			array			$arrParams				页码跳转需要带的参数
	 * @param			int				$intPagesize			每页显示的行数，默认为：20行
	 * @return			array			$strPage				返回分页的数据			
	 */
	public function getPageData($condition = "", $arrParams = array(), $intPagesize = 20) {
		if (! empty ( $condition )) {
			$model = M ();
			$condition_where = preg_replace ( "/(select|SELECT)(.*)(from|FROM)/", "SELECT COUNT(*) AS NUM FROM  ", $condition );
			$count_result = $model->query ( $condition_where . " limit 1" );
			$intDataCount = $count_result ? $count_result [0] ['num'] : 0; 
			$objPage = new \Think\Page ( $intDataCount, $intPagesize );
			$objPage->setConfig ( 'first', '首页' );
			$objPage->setConfig ( 'header', '' );
			$objPage->setConfig ( 'prev', '上一页' );
			$objPage->setConfig ( 'theme', '<li><a>共 %TOTAL_ROW%条 %HEADER% %NOW_PAGE%/%TOTAL_PAGE% 页 </a></li>%FIRST% %UP_PAGE% %LINK_PAGE%  %DOWN_PAGE% %END%' );
			$objPage->setConfig ( 'next', '下一页' );
			$objPage->setConfig ( 'last', '尾页' );
			$objPage->parameter = ! empty ( $arrParams ) ? http_build_query ( $arrParams ) : '';
			$strPage = $objPage->fshow ();
			$result = $model->query ( $condition . " limit " . $objPage->firstRow . "," . $objPage->listRows );
			return array ("data" => $result, "page" => $strPage, "total" => $intDataCount );
		}
		return FALSE;
	}
	
	/**
	 * 上传图片方法
	 * 
	 * @param Array 	$file_name	   上传的key，file表单的名称
	 * @param string    $patch		  上传的路径
	 * @return Array 	$row		 返回上传的路径
	 */
	public function uploadImg($file_name = array(), $patch) {
		if (! $_FILES)
			return false;
		$config = array ('maxSize' => C ( 'UPLOAD_IMG_SIZE' ), 'rootPath' => UPLOAD_PATH, 'savePath' => $patch . '/', 'saveName' => array ('uniqid', '' ), 'exts' => array ('jpg', 'gif', 'png', 'jpeg' ), 'autoSub' => true, 'subName' => date("Y")."/".date("m")."/".date("d") );
		$data = array ();
		$upload = new \Think\Upload ( $config );
		foreach ( $file_name as $key => $value ) {
			$thumb = $_FILES [$value];
			if (! empty ( $thumb ['size'] )) {
				@unlink ( "." . $_POST [$value . "_txt"] ); //删除原先缩略图
				$info = $upload->uploadOne ( $thumb );
				if ($info) {
					$litpic = UPLOAD_PATH . $info ['savepath'] . $info ['savename'];
					chmod ( $litpic, 0777 );
					$jumpurl = str_replace ( "./", "/", $litpic );
					$data [$value] = $jumpurl;
				} else {
					$data [$value] =array('status'=>'0','errorMsg'=>$upload->getError ());
				}
			} else {
				$data [$value] = $_POST [$value . "_txt"];
			}
		}
		return $data;
	}
}