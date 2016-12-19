<?php
namespace Jzadmin\Controller;
class SysDataController extends CommonController {
	
	Protected $autoCheckFields = false;
	/**
	 * 数据备份
	 * Enter description here ...
	 */
	public function index() {
		
		$M = M ();
		$tabs = $M->query ( 'SHOW TABLE STATUS' ); 
		$total = 0;
		foreach ( $tabs as $k => $v ) {
			$tabs [$k] ['size'] = byteFormat ( $v ['data_length'] + $v ['index_length'] );
			$total += $v ['data_length'] + $v ['index_length'];
		}
		 
		$this->assign ( "list", $tabs );
		$this->assign ( "total", byteFormat ( $total ) );
		$this->assign ( "tables", count ( $tabs ) );
		$this->display ();
	}
	
	/**
      +----------------------------------------------------------
	 * 备份数据库
      +----------------------------------------------------------
	 */
	public function backup() {
		if (! IS_POST)
			$this->error ( "访问出错啦" );
		$M = M ();
		function_exists ( 'set_time_limit' ) && set_time_limit ( 0 ); //防止备份数据过程超时
		$tables = empty ( $_POST ['table'] ) ? array () : $_POST ['table'];
		if (count ( $tables ) == 0 && ! isset ( $_POST ['systemBackup'] )) {
			$this->error ( "请先选择要备份的表" );
		}
		$time = time ();
		if (isset ( $_POST ['systemBackup'] )) {
			if ($_SESSION ['role_id'] != C ( "ADMIN_ROLE_ID" )) {
				$this->error ( "只有超级管理员账号登录后方可自动备份操作" );
			}
			$type = "系统自动备份";
			$tables = D ( "SysData" )->getAllTableName ();
			$path = C ( "DatabaseBackDir" ) . "/SYSTEM_" . date ( "Ym" );
			if (file_exists ( $path . "_1.sql" )) {
				$this->error ( "本月度系统已经进行了自动备份操作" );
			}
		} else {
			$type = "管理员后台手动备份";
			$path = C ( "DatabaseBackDir" ) . "/CUSTOM_" . date ( "Ymd" ) . "_" . randCode ( 5 );
		}
		$pre = "# -----------------------------------------------------------\n" . "# PHP-Amateur database backup files\n" . "# web: http://".C('SITE_URL')."\n" . "# Type: {$type}\n";
		$bdTable = D ( "SysData" )->bakupTable ( $tables ); //取得表结构信息
		$outPut = "";
		$file_n = 1;
		$backedTable = array ();
		foreach ( $tables as $table ) {
			$backedTable [] = $table;
			$outPut .= "\n\n# 数据库表：{$table} 数据信息\n";
			$tableInfo = $M->query ( "SHOW TABLE STATUS LIKE '{$table}'" ); 
			$page = ceil ( $tableInfo [0] ['rows'] / 10000 ) - 1;  
			for($i = 0; $i <= $page; $i ++) {
				$query = $M->query ( "SELECT * FROM {$table} LIMIT " . ($i * 10000) . ", 10000" ); 
				foreach ( $query as $val ) {
					$temSql = "";
					$tn = 0;
					$temSql = '';
					foreach ( $val as $v ) {
						$temSql .= $tn == 0 ? "" : ",";
						$temSql .= $v == '' ? "''" : "'{$v}'";
						$tn ++;
					}
					$temSql = "INSERT INTO `{$table}` VALUES ({$temSql});\n";
					
					$sqlNo = "\n# Time: " . date ( "Y-m-d H:i:s" ) . "\n" . "# -----------------------------------------------------------\n" . "# 当前SQL卷标：#{$file_n}\n# -----------------------------------------------------------\n\n\n";
					if ($file_n == 1) {
						$sqlNo = "# Description:当前SQL文件包含了表：" . implode ( "、", $tables ) . "的结构信息，表：" . implode ( "、", $backedTable ) . "的数据" . $sqlNo;
					} else {
						$sqlNo = "# Description:当前SQL文件包含了表：" . implode ( "、", $backedTable ) . "的数据" . $sqlNo;
					}
					if (strlen ( $pre ) + strlen ( $sqlNo ) + strlen ( $bdTable ) + strlen ( $outPut ) + strlen ( $temSql ) > C ( "sqlFileSize" )) {
						$file = $path . "_" . $file_n . ".sql";
						$outPut = $file_n == 1 ? $pre . $sqlNo . $bdTable . $outPut : $pre . $sqlNo . $outPut;
						file_put_contents ( $file, $outPut, FILE_APPEND );
						$bdTable = $outPut = "";
						$backedTable = array ();
						$backedTable [] = $table;
						$file_n ++;
					}
					$outPut .= $temSql;
				}
			}
		}
		if (strlen ( $bdTable . $outPut ) > 0) {
			$sqlNo = "\n# Time: " . date ( "Y-m-d H:i:s" ) . "\n" . "# -----------------------------------------------------------\n" . "# 当前SQL卷标：#{$file_n}\n# -----------------------------------------------------------\n\n\n";
			if ($file_n == 1) {
				$sqlNo = "# Description:当前SQL文件包含了表：" . implode ( "、", $tables ) . "的结构信息，表：" . implode ( "、", $backedTable ) . "的数据" . $sqlNo;
			} else {
				$sqlNo = "# Description:当前SQL文件包含了表：" . implode ( "、", $backedTable ) . "的数据" . $sqlNo;
			}
			$file = $path . "_" . $file_n . ".sql";
			$outPut = $file_n == 1 ? $pre . $sqlNo . $bdTable . $outPut : $pre . $sqlNo . $outPut;
			file_put_contents ( $file, $outPut, FILE_APPEND );
			$file_n ++;
		}
		$time = time () - $time;
		$this->success ( "成功备份所选数据库表结构和数据，本次备份共生成了" . ($file_n - 1) . "个SQL文件。耗时：{$time} 秒", U ( "index" ) );
	
	}
	
	/**
      +----------------------------------------------------------
	 * 还原数据库内容
      +----------------------------------------------------------
	 */
	public function restore() {	 
		$admin_node_model = D ( "AdminNode" );
		$data = 	D ( "SysData" )->getSqlFilesList ();
		$this->assign("position",$admin_node_model->getPosition());
		$this->assign ( "list", $data ['list'] );
		$this->assign ( "total", $data ['size'] );
		$this->assign ( "files", count ( $data ['list'] ) );
		$this->display ();
	}
	
	/**
      +----------------------------------------------------------
	 * 列出以打包sql文件
      +----------------------------------------------------------
	 */
	public function zipList() {
		$admin_node_model = D ( "AdminNode" );
		$data = D ( "SysData" )->getZipFilesList ();
		$this->assign("position",$admin_node_model->getPosition());
		$this->assign ( "list", $data ['list'] );
		$this->assign ( "total", $data ['size'] );
		$this->assign ( "files", count ( $data ['list'] ) );
		$this->display ();
	}
	
	/**
	 * +--------------------------------------------------------
	 * 数据库优化
	 * +--------------------------------------------------------
	 */
	public function repair() {
		$M = M ( "Admin" );
		if (IS_POST) {
			if (empty ( $_POST ['table'] ) || count ( $_POST ['table'] ) == 0) {
				$this->error("请选择要处理的表");
			}
			$table = implode ( ',', $_POST ['table'] );
			if ($_POST ['act'] == 'repair') {
				if ($M->query ( "REPAIR TABLE {$table} " ))
				$this->success("修复表成功",U ( 'SysData/repair' ) );
			} elseif ($_POST ['act'] == 'optimize') {
				if ($M->query ( "OPTIMIZE TABLE $table" ))
					$this->success("优化表成功",U ( 'SysData/repair' ) );
			}else{
				$this->error("请选择操作");
			}
		} else {
			$tabs = $M->query ( 'SHOW TABLE STATUS' );
			$totalsize = array ('table' => 0, 'index' => 0, 'data' => 0, 'free' => 0 );
			$tables = array ();
			foreach ( $tabs as $k => $table ) {
				$table ['size'] = byteFormat ( $table ['data_length'] + $table ['index_length'] );
				$totalsize ['table'] += $table ['data_length'] + $table ['index_length'];
				$totalsize ['data'] += $table ['data_length'];
				$table ['data_length'] = byteFormat ( $table ['data_length'] );
				$totalsize ['index'] += $table ['Index_length'];
				$table ['index_length'] = byteFormat ( $table ['index_length'] );
				$totalsize ['free'] += $table ['data_free']; 
				$table ['data_free'] = byteFormat ( $table ['data_free'] );
				$tables [] = $table;
			}
			  
			$this->assign ( "list", $tables );
			$this->assign ( "totalsize", $totalsize );
			$this->display ();
		}
	}
	
	/**
      +----------------------------------------------------------
	 * 读取要导入的sql文件列表并排序后插入SESSION中
      +----------------------------------------------------------
	 */
	static private function getRestoreFiles() {
		$_SESSION ['cacheRestore'] ['time'] = time ();
		if (empty ( $_GET ['sqlPre'] ))
			return false;
		
		//获取sql文件前缀
		$sqlPre = $_GET ['sqlPre'];
		$handle = opendir ( C ( "DatabaseBackDir" ) );
		$sqlFiles = array ();
		while ( $file = readdir ( $handle ) ) {
			//获取以$sqlPre为前缀的所有sql文件
			if (preg_match ( '#\.sql$#i', $file ) && preg_match ( '#' . $sqlPre . '#i', $file ))
				$sqlFiles [] = $file;
		}
		closedir ( $handle );
		if (count ( $sqlFiles ) == 0)
			return false;
		
		//将要还原的sql文件按顺序组成数组，防止先导入不带表结构的sql文件
		$files = array ();
		foreach ( $sqlFiles as $sqlFile ) {
			$k = str_replace ( ".sql", "", str_replace ( $sqlPre . "_", "", $sqlFile ) );
			$files [$k] = $sqlFile;
		}
		unset ( $sqlFiles, $sqlPre );
		ksort ( $files );
		$_SESSION ['cacheRestore'] ['files'] = $files;
		return $files;
	}
	
	/**
      +----------------------------------------------------------
	 * 执行还原数据库操作
      +----------------------------------------------------------
	 */
	public function restoreData() {
		//        ini_set("memory_limit", "256M");
		function_exists ( 'set_time_limit' ) && set_time_limit ( 0 ); //防止备份数据过程超时
		//取得需要导入的sql文件
		$files = isset ( $_SESSION ['cacheRestore'] ) ? $_SESSION ['cacheRestore'] ['files'] : self::getRestoreFiles ();
		//取得上次文件导入到sql的句柄位置
		$position = isset ( $_SESSION ['cacheRestore'] ['position'] ) ? $_SESSION ['cacheRestore'] ['position'] : 0;
		$M = M ( "Admin" );
		$execute = 0;
		foreach ( $files as $fileKey => $sqlFile ) {
			$file = C ( "DatabaseBackDir" ) . "/" . $sqlFile;
			if (! file_exists ( $file ))
				continue;
			$file = fopen ( $file, "r" );
			$sql = "";
			fseek ( $file, $position ); //将文件指针指向上次位置
			while ( ! feof ( $file ) ) {
				$tem = trim ( fgets ( $file ) );
				//过滤掉空行、以#号注释掉的行、以--注释掉的行
				if (empty ( $tem ) || $tem [0] == '#' || ($tem [0] == '-' && $tem [1] == '-'))
					continue;
				
		//统计一行字符串的长度
				$end = ( int ) (strlen ( $tem ) - 1);
				//检测一行字符串最后有个字符是否是分号，是分号则一条sql语句结束，否则sql还有一部分在下一行中
				if ($tem [$end] == ";") {
					$sql .= $tem;
					$M->execute ( $sql );
					$sql = "";
					$execute ++;
					if ($execute > 500) {
						$_SESSION ['cacheRestore'] ['position'] = ftell ( $file );
						$imported = isset ( $_SESSION ['cacheRestore'] ['imported'] ) ? $_SESSION ['cacheRestore'] ['imported'] : 0;
						$imported += $execute;
						$_SESSION ['cacheRestore'] ['imported'] = $imported;
						$this->success ( "如果导入SQL文件卷较大(多)导入时间可能需要几分钟甚至更久，请耐心等待导入完成，导入期间请勿刷新本页，当前导入进度：<font color=\"red\">已经导入' . $imported . '条Sql</font>", U ( 'SysData/restoreData', array (randCode () => randCode () ) ) );
						exit ();
					}
				} else {
					$sql .= $tem;
				}
			}
			fclose ( $file );
			unset ( $_SESSION ['cacheRestore'] ['files'] [$fileKey] );
			$position = 0;
		}
		$time = time () - $_SESSION ['cacheRestore'] ['time'];
		unset ( $_SESSION ['cacheRestore'] );
		$this->success ( "导入成功，耗时：{$time} 秒钟" ,U('restore'));
	}
	
	/**
      +----------------------------------------------------------
	 * 删除已备份数据库文件
      +----------------------------------------------------------
	 */
	public function delSqlFiles() {
		if (empty ( $_POST ['sqlFiles'] ) || count ( $_POST ['sqlFiles'] ) == 0)
			$this->error ( "请先选择要删除的文件" );
		
		$files = ($_POST ['sqlFiles']);
		foreach ( $files as $file ) {
			delDirAndFile ( C ( "DatabaseBackDir" ) . "/" . $file );
		}
		$this->success("已删除：" . implode ( "、", $files ), U('restore'));
	}
	
   /**
      +----------------------------------------------------------
     * 打包sql文件
      +----------------------------------------------------------
     */
    public function zipSql() {
        if (IS_POST) {
            if (!$_POST['sqlFiles'] || count($_POST['sqlFiles']) == 0)
               $this->error("请选择要打包的sql文件");
            $files = $_POST['sqlFiles'];
            $toZip = array(); 
            foreach ($files as $file) {
                $tem = explode("_", $file);
                unset($tem[count($tem) - 1]);
                $toZip[implode("_", $tem)][] = $file;
            } 
            foreach ($toZip as $zipOut => $files) {
                if (D("SysData")->zip($files, $zipOut . ".zip",  INCLUDE_PATH."Zip/",C ( "DatabaseBackDir" ))) {
                    foreach ($files as $file) {
                        delDirAndFile(INCLUDE_PATH."Zip/" . $file);
                    }
                }
            }
            $this->success("打包的sql文件成功，本次打包" . count($toZip) . "个zip文件",U('SysData/zipList'));

        }
    }
     /**
      +----------------------------------------------------------
     * 删除压缩包
      +----------------------------------------------------------
     */
    function unzipSqlfile() {
        if (!IS_POST)
            return FALSE;
        if ($_SESSION['unzip']) {
            $files = $_SESSION['unzip']['files'];
        } else {
            $_SESSION['unzip']['time'] = time();
            if (!$_POST['zipFiles'] || count($_POST['zipFiles']) == 0)
                $this->error("请选择要解压的zip文件");
            $files = $_POST['zipFiles'];
            $_SESSION['unzip']['files'] = $files;
            $_SESSION['unzip']['count'] = count($files);
        }
        foreach ($files as $k => $file) {
            D("SysData")->unzip($file,C("DatabaseBackDir"));
            
            if (count($files) > 1) {
            	$this->success("正在解压缩请耐心等待，解压期间请勿刷新本页 <font color=\"red\">当前已经解压完{$file}</font>",U('SysData/unzipSqlfile', array(randCode() => randCode())) ); 
                unset($_SESSION['unzip']['files'][$k]);
                exit;
            }
        }

        $time = time() - $_SESSION['unzip']['time'];
        unset($_SESSION['unzip']);
        $this->success("已解压完成,耗时：$time 秒", U('SysData/restore'));
    }
    
    /**
      +----------------------------------------------------------
     * 删除已备份数据库文件
      +----------------------------------------------------------
     */
    public function delZipFiles() {
        if (IS_POST) {
            if (!$_POST['zipFiles'] || count($_POST['zipFiles']) == 0)
                $this->error("请选择要删除的zip文件");
            $files = $_POST['zipFiles'];
            foreach ($files as $file) {
                delDirAndFile( INCLUDE_PATH."Zip/" . $file);
            }
            $this->success("已删除：" . implode("、", $files), U('zipList'));
        }
    }
    
    /**
     * 下载文件
     * Enter description here ...
     */
    public function downFile() {
        if (empty($_GET['file']) || empty($_GET['type']) || !in_array($_GET['type'], array("zip", "sql"))) {
            $this->error("下载地址不存在");
        }
        $path = array("zip" =>  INCLUDE_PATH."Zip", "sql" => C("DatabaseBackDir"));
        $filePath = $path[$_GET['type']] ."/". $_GET['file'];
        if (!file_exists($filePath)) {
            $this->error("该文件不存在，可能是被删除");
        }
        $filename = basename($filePath);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
    }
   
}