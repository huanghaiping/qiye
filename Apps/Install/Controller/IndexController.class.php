<?php
namespace Install\Controller;
class IndexController extends CommonController {
	
	public function index() {
		$this->display();
	}
	/**
	 * +-------------------------------------------
	 * 安装完成
	 * +-------------------------------------------
	 */
    public function complete(){
        $step = session('step');

        if(!$step){
            $this->redirect('index');
        } elseif($step != 3) {
            $this->redirect("Install/step{$step}");
        }

        // 写入安装锁定文件
        file_put_contents(INCLUDE_PATH.'/install.lock', 'lock');
        if(!session('update')){
            //创建配置文件
            $this->assign('info',session('config_file'));
        }
		$site_model=D(ADMIN_NAME."/Site")->updateConfig();
		D(ADMIN_NAME."/Menu")->updateCache();
        session('step', null);
        session('error', null);
        session('update',null);
        $this->display();
    }
}