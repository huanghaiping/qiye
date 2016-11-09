<?php
/**
 * 地区模型 - 数据对象模型
 * @author Alan <451648237@qq.com> 
 */
namespace Home\Model;
class AreaModel extends CommonModel {

 
	/**
	 * +----------------------------------------------------------
	 * 当指定pid时，查询该父地区的所有子地区；否则查询所有地区
	 * +----------------------------------------------------------
	 * 
	 * @param integer $pid 父地区ID
	 * @return array 相应地区列表
	 */
	public function getAreaList($pid = 0) {
		$map = array();
		$map['parent_id'] = $pid;
		$data = $this->where($map)->order('`id` ASC')->select(); 
		return $data;
	}
	
	/**
	 * +----------------------------------------------------------
	 * 获取地区的树形结构 - 目前为两级结构 - TODO
	 * +----------------------------------------------------------
	 * @param integer $pid 地区的父级ID
	 * @return array 指定父级ID的树形结构
	 */
	public function getAreaTree($pid) {
		$output	= array();
		$list = $this->getAreaList();
		// 获取省级
		foreach($list as $k1 => $p) {
			if($p['parent_id'] == 1) {
				// 获取当前省的市
				$city = array();
				foreach($list as $k2 => $c) {
					if($c['parent_id'] == $p['id']) {
						$city[] = array($c['id'] => $c['area_name']);
						unset($list[$k2]);
					}
				}
				$output['provinces'][] = array(
					'id' => $p['id'],
					'name' => $p['area_name'],
					'citys' => $city,
				);
				unset($list[$k1], $city);
			}
		}
		unset($list);
		return $output;
	}

	/**
	 * +----------------------------------------------------------
	 * 获取指定地区ID下的地区信息
	 * +----------------------------------------------------------
	 * @param integer $id 地区ID
	 * @return array 指定地区ID下的地区信息
	 */
	public function getAreaById($ids) {
		$result = array();
		if(!empty($ids)) {
			if(is_array($ids)){
				$map['id']=array("in",$ids);
			}elseif(strpos($ids, ",")){
				$id_array=explode(",", $ids);
				$map['id']=array("in",$ids);
			}else{
				$map['id'] = $ids;
			}
			$result = $this->where($map)->select();
		}

		return $result;
	}
	
	/**
	 * +----------------------------------------------------------
	 * 获取指定父地区的树形结构
	 * +----------------------------------------------------------
	 * @param integer $pid 父地区ID
	 * @return array 指定树形结构
	 */
	public function getNetworkList($pid = '0') {
		// 子地区树形结构
		if($pid != 0) {
			return $this->_MakeTree($pid);
		}
		// 全部地区树形结构
		$list = S('city');
		if(empty($list)) {
			set_time_limit(0);
			$list = $this->_MakeTree($pid);
			S('city', $list);
		}
	
		return $list;
	}
	
	/**
	 * +----------------------------------------------------------
	 * 清除地区数据PHP文件
	 * +----------------------------------------------------------
	 * @return void
	 */
	public function remakeCityCache() {
		S('city', null);
	}

	/**
	 * +----------------------------------------------------------
	 * 递归形成树形结构
	 * @param integer $pid 父级ID
	 * @param integer $level 等级
	 * @return array 树形结构
	 */
	private function _MakeTree($pid, $level = '0') {
		$result = $this->where('parent_id='.$pid)->select();
		if($result) {
			foreach($result as $key => $value) {
				$id = $value['id'];
				$list[$id]['id'] = $value['id'];
				$list[$id]['pid'] = $value['parent_id'];
				$list[$id]['title'] = $value['area_name'];
				$list[$id]['level'] = $level;
				$list[$id]['child'] = $this->_MakeTree($value['area_id'], $level + 1);
			}
		}

		return $list;
	}
}