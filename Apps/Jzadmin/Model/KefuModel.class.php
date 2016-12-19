<?php
namespace Jzadmin\Model;
class KefuModel extends CommonModel {
	
	protected $_validate = array (array ('name', 'require', '客服名称不能为空', 1 ) );
	
	/**
	 * +-----------------------------------------
	 * 验证模型字段
	 * +-----------------------------------------
	 * @param Array $postData	POST提交上来的数据
	 * 
	 * @return Array 返回过滤后的数组
	 */
	public function createData($postData) {
		$data = array ();
		$data ['ctime'] = time ();
		$data ['name'] = isset ( $postData ['name'] ) ? addSlashesFun ( $postData ['name'] ) : "";
		$data ['typeid'] = isset ( $postData ['typeid'] ) ? intval ( $postData ['typeid'] ) : "";
		$data ['linktype'] = isset ( $postData ['linktype'] ) ? intval ( $postData ['linktype'] ) : "";
		$data ['status'] = isset ( $postData ['status'] ) ? intval ( $postData ['status'] ) : "";
		$data ['listorder'] = isset ( $postData ['listorder'] ) ? intval ( $postData ['listorder'] ) : "";
		$data ['content'] = isset ( $postData ['content'] ) ? addSlashesFun ( $postData ['content'] ) : "";
		$data ['lang'] = $this->lang;
		$file_data = $this->uploadImg ( array ('logo' ), 'ad' );
		if (! empty ( $file_data ['logo'] )) {
			$data ['logo'] = $file_data ['logo'];
		} else {
			$data ['logo'] = "";
		}
		$pay_config_id_array = $postData ['pay_config_id'];
		if (is_array ( $pay_config_id_array )) {
			$row = array ();
			foreach ( $pay_config_id_array as $key => $value ) {
				if (! empty ( $postData ['pay_config_value_' . $value] )) {
					$row [$key] = array ('id' => $value, "key" => $postData ['pay_config_param_' . $value], "value" => $postData ['pay_config_value_' . $value],"text"=>$postData ['pay_config_text_' . $value] );
				}
				unset ( $postData ['pay_config_param_' . $value] );
				unset ( $postData ['pay_config_value_' . $value] );
				unset ( $postData ['pay_config_text_' . $value] );
			}
			$data ['pay_config'] = serialize ( $row );
			unset ( $pay_config_id_array );
		}
		return $data;
	}
	
	/**
	 * +--------------------------------------
	 * 获取客服的类型
	 * +--------------------------------------
	 * 
	 * $typeId int	客服类型ID
	 * 
	 */
	public function getType($typeId = 0) {
		$type = array ("1" => "QQ", "2" => "MSN", "7" => "skype", "3" => "旺旺", "4" => "电话", "5" => "代码", "6" => "微信" );
		return empty ( $typeId ) ? $type : $type [$typeId];
	}
	
	/**
	 * +--------------------------------------
	 * 获取客服的图标
	 * +--------------------------------------
	 * 
	 * $typeId int	客服类型ID
	 * 
	 */
	public function getSkin($skinId){
		$skin_array=array(
			"1"=>array('id'=>"1","title"=>"QQ风格一","thumb"=>"01_online.gif","type"=>1),
			"2"=>array('id'=>"2","title"=>"QQ风格二","thumb"=>"02_online.gif","type"=>1),
			"3"=>array('id'=>"3","title"=>"QQ风格三","thumb"=>"03_online.gif","type"=>1),
			"4"=>array('id'=>"4","title"=>"QQ风格四","thumb"=>"04_online.gif","type"=>1),
			"5"=>array('id'=>"5","title"=>"QQ风格五","thumb"=>"05_online.gif","type"=>1),
			"6"=>array('id'=>"6","title"=>"QQ风格六","thumb"=>"6_online.gif","type"=>1),
			"7"=>array('id'=>"7","title"=>"QQ风格七","thumb"=>"7_online.gif","type"=>1),
			"8"=>array('id'=>"8","title"=>"QQ风格八","thumb"=>"8_online.gif","type"=>1),
			"9"=>array('id'=>"9","title"=>"QQ风格九","thumb"=>"9_online.gif","type"=>1),
			"10"=>array('id'=>"10","title"=>"QQ风格十","thumb"=>"10_online.gif","type"=>1),
			"11"=>array('id'=>"11","title"=>"QQ风格十一","thumb"=>"11_online.gif","type"=>1),
			"12"=>array('id'=>"12","title"=>"QQ风格十二","thumb"=>"12_online.gif","type"=>1),
			"13"=>array('id'=>"13","title"=>"QQ风格十三","thumb"=>"13_online.gif","type"=>1),
			
			"14"=>array('id'=>"14","title"=>"旺旺风格一","thumb"=>"ww_01.gif","type"=>3),
			"15"=>array('id'=>"15","title"=>"旺旺风格二","thumb"=>"ww_02.gif","type"=>3),
			"16"=>array('id'=>"16","title"=>"旺旺风格三","thumb"=>"ww_03.gif","type"=>3),
			"17"=>array('id'=>"17","title"=>"旺旺风格四","thumb"=>"ww_04.gif","type"=>3),
			
			"18"=>array('id'=>"18","title"=>"skype风格一","thumb"=>"skype_01.png","type"=>7),
			"19"=>array('id'=>"19","title"=>"MSN风格一","thumb"=>"msn1.gif","type"=>2),
			"20"=>array('id'=>"20","title"=>"MSN风格二","thumb"=>"msn2.gif","type"=>2),
			"21"=>array('id'=>"21","title"=>"MSN风格三","thumb"=>"msn3.gif","type"=>2),
			"22"=>array('id'=>"22","title"=>"MSN风格四","thumb"=>"msn4.gif","type"=>2),
		);
		return empty ( $skinId ) ? $skin_array : $skin_array [$skinId];
	}

}