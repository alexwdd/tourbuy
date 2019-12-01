<?php
namespace app\adminx\controller;

class Setting extends Admin {

    public function index(){
		/*配置列表*/
		$group_list = array('basic'=>'基本设置','member'=>'商城设置','supay'=>'SUPAY','weixin'=>'微信设置','sms'=>'短信设置','kuaidi'=>'物流设置');		
		$this->assign('group_list',$group_list);
		$inc_type =  input('inc_type','basic');
		$this->assign('inc_type',$inc_type);
		$config = tpCache($inc_type);
		$this->assign('config',$config);//当前配置项
        config('TOKEN_ON',false);
		return view($inc_type);
	}
	
    #保存配置
    public function insert(){
    	$param = input('post.');
		$inc_type = $param['inc_type'];
		unset($param['inc_type']);
		if($param['hotkey']){
			$param['hotkey'] = str_replace("，",",",$param['hotkey']);
		}

		tpCache($inc_type,$param);
		$this->success("操作成功",url('Setting/index',array('inc_type'=>$inc_type)));
    }
}