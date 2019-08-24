<?php
namespace app\api\controller;

class Auth extends Common
{
    
	public function _initialize(){
        parent::_initialize();
        if($this->user['id']==0){
        	returnJson('9001','登录超时，请重新登录');
        }
    } 
}
