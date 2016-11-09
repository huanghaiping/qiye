<?php
/**
 * +-------------------------------------------
 * 广告的模型类
 * +-------------------------------------------
 * @author Alan
 */
namespace Jzadmin\Model;
class AdModel extends CommonModel {
	
	protected $_validate = array (array ('adid', 'require', '请输入广告位别名', 1 ), array ('adname', 'require', '请输入广告的别名', 1 ) );
	
	/**
	 * +-----------------------------------------
	 * 验证广告模型字段
	 * +-----------------------------------------
	 * @param Array $postData	POST提交上来的数据
	 * 
	 * @return Array 返回过滤后的数组
	 */
	public function createData($postData) {
		$data = array ();
		$data ['adid'] = isset ( $postData ['adid'] ) ? addSlashesFun ( $postData ['adid'] ) : "";
		$data ['adname'] = isset ( $postData ['adname'] ) ? addSlashesFun ( $postData ['adname'] ) : "";
		$data ['typeid'] = isset ( $postData ['typeid'] ) ? intval ( $postData ['typeid'] ) : "";
		$data ['normbody'] = isset ( $postData ['normbody'] ) ? addSlashesFun ( $postData ['normbody'] ) : "";
		$data ['title'] = isset ( $postData ['title'] ) ? addSlashesFun ( $postData ['title'] ) : "";
		$data ['url'] = isset ( $postData ['url'] ) ? addSlashesFun ( $postData ['url'] ) : "";
		$data ['lang'] = $this->lang;
		$data ['ctime'] = time ();
		
		switch ($data ['typeid']) {
			//保存文字
			case 2 :
				$data ['normbody'] =  $data ['normbody'] ;
				break;
			
			//保存图片
			case 3 :
				$data ['imgwidth'] = isset ( $postData ['imgwidth'] ) ? $postData ['imgwidth'] : "";
				$data ['imgheight'] = isset ( $postData ['imgheight'] ) ? $postData ['imgheight'] : "";
				$data ['istitle'] = isset ( $postData ['istitle'] ) ? intval ( $postData ['istitle'] ) : 0;
				$imgurl = $this->uploadImg ( array ('imgurl' ), 'ad' );
				if (!empty($imgurl['imgurl'])){
					$data ['imgurl'] = $imgurl ['imgurl'];
				}
				$data ['normbody'] = $data ['imgwidth'] . "," . $data ['imgheight'] . "," . $data ['istitle'];
				break;
			//保存flash		
			case 4 :
				$data ['url'] = isset ( $postData ['flashurl'] ) ? $postData ['flashurl'] : "";
				$flashwidth = isset ( $postData ['flashwidth'] ) ? $postData ['flashwidth'] : "";
				$flashheight = isset ( $postData ['flashheight'] ) ? $postData ['flashheight'] : "";
				$data ['normbody'] = $flashwidth . "," . $flashheight;
				break;
		
		}
		return $data;
	}
	
	/**
	 * +-----------------------------------------
	 * 验证幻灯片提交的数据
	 * +-----------------------------------------
	 * @param Array $postData	POST提交上来的数据
	 * 
	 * @return Array 返回过滤后的数组
	 */
	public function createDataBySlide($postData){
		$data = array ();
		$data['ctime']=time();
		$data['typeid']= isset($_POST ['typeid']) ? intval ( $_POST ['typeid'] ) : "";
		$data['title']=isset($_POST ['title']) ? addSlashesFun ( $_POST ['title'] ) : "";
		$data['linkurl'] = isset($_POST ['linkurl']) ? addSlashesFun($_POST ['linkurl']) : "";
		$data['typeid']= isset($_POST ['typeid']) ? intval ( $_POST ['typeid'] ) : "";
		$data['width']=isset($_POST['imgwidth']) ? intval($_POST ['imgwidth']) : "";
		$data['height']=isset($_POST['imgheight']) ? intval($_POST ['imgheight']) : "";
		$file_data = $this->uploadImg ( array ('picurl' ), 'ad' ); 
		if (!empty($file_data['picurl'])){
			$data['picurl'] = $file_data['picurl'];
		}else{
			$this->error='上传图片不能为空';
			return false;
		}
		$data['sortslide']=isset($_POST['sortslide']) ? intval($_POST ['sortslide']) : "";
		$data['product_title'] = isset($_POST ['product_title']) ? addSlashesFun($_POST ['product_title']) : "";
		$data['product_description'] = isset($_POST ['product_description']) ? addSlashesFun($_POST ['product_description']) : "";
		return $data;
	}
	
}