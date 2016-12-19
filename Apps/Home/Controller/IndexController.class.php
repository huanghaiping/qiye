<?php
namespace Home\Controller;
class IndexController extends CommonController {
    public function index(){
    	$this->get_seo_info();
        $this->display();
    }
}