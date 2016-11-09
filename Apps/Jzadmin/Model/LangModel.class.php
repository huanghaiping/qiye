<?php
namespace Jzadmin\Model;
/**
 * 
 * 菜单操作的模型类
 * @author Alan
 *
 */
class LangModel extends CommonModel {
	
	/**
	 * 
	 * 获取当前正在使用的语言
	 */
	public function getLang() {
		$lang_list = F ( 'Lang' ,'',INCLUDE_PATH);
		if (! $lang_list) {
			$lang_list = $this->updateCache ();
		}
		return $lang_list;
	}
	
	/**
	 * 
	 * 更新语言的缓存文件
	 *
	 */
	public function updateCache() {
		$lang_list = $this->where ( "status=0" )->order ( "listorder desc,id desc" )->select ();
		$row=array();
		foreach ( $lang_list as $key => $value ) {
			if (! empty ( $value ['domain'] )) {
				$domain = strpos ( $value ['domain'], "," ) ? explode ( ",", $value ['domain'] ) : array ($value ['domain'] );
				$value ['url'] = "http://" . $domain [0];
			} else {
				if (C ( 'URL_MODEL' ) == 2) {
					$value ['url'] = "/" . $value ['mark'];
				} else {
					$value ['url'] = C ( 'SITE_URL' ) . "/?".C ( 'VAR_LANGUAGE', null, 'l' )."=" . $value ['mark'];
				}
			}
			$row [$value ['mark']] = $value;
		}
		unset ( $lang_list );
		F ( 'Lang', $row,INCLUDE_PATH );
		$module_model=D(ADMIN_NAME."/Module");
		$module_model->createRoutes($row);
		return $row;
	}
	
	/**
	 * 更新语言包文件
	 * 
	 * @param int 	 	$lang_id	语言ID
	 * @param string 	$mark  		语言标识
	 * 
	 * @return bool
	 */
	public function updateLangCache($lang_id, $mark) {
		if (empty ( $lang_id ))
			return false;
		$lang_param_model = D ( "LangParam" );
		$copy_lang_list = $lang_param_model->where ( "lang_id='" . $lang_id . "'" )->select ();
		$file_put_content = array ();
		if ($copy_lang_list) {
			foreach ( $copy_lang_list as $value ) {
				$file_put_content [$value ['field']] = $value ['value'];
			}
		}
		//生成文件
		$file_url_name = APP_PATH . C ( "DEFAULT_MODULE" ) . "/Lang/" . $mark . ".php";
		$config = "<?php\r\n \r\nreturn \$array = " . var_export ( $file_put_content, TRUE ) . ";\r\n?>";
		if (! file_exists_case ( $file_url_name )) {
			$myfile = fopen ( $file_url_name, "w" );
			fwrite ( $myfile, $config );
			fclose ( $myfile );
			chmod ( $file_url_name, 0777 );
		} else {
			file_put_contents ( $file_url_name, $config );
		}
		return true;
	
	}
	
	/**
	 * 更改国家图标的时候更新文件
	 *
	 * @param int	 	$old_id			语言ID
	 * @param string	$old_mark		语言旧标识
	 * @param string	$new_mark		语言新标识
	 * 
	 * @return bool
	 */
	public function changMark($old_id, $old_mark, $new_mark) {
		if (empty ( $old_id ))
			return false;
		$lang_param_model = D ( "LangParam" );
		$result = $lang_param_model->where ( "lang_id='" . $old_id . "'" )->save ( array ('mark' => $new_mark ) );
		if ($result) {
			$file_old_name = APP_PATH . C ( "DEFAULT_MODULE" ) . "/Lang/" . $old_mark . ".php";
			$file_new_name = APP_PATH . C ( "DEFAULT_MODULE" ) . "/Lang/" . $new_mark . ".php";
			rename ( $file_old_name, $file_new_name );
		}
		return true;
	}
}