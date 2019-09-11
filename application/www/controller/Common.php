<?php
namespace app\www\controller;

use app\common\controller\Base;

class Common extends Base {

	public $is_weixin;

	public function _initialize(){
        parent::_initialize();

        $httpAgent = $_SERVER['HTTP_USER_AGENT']; 
        if(strpos(strtolower($httpAgent),"micromessenger")) {
            $this->is_weixin = true;
        }else{
            $this->is_weixin = false;
        }
    }
    
}