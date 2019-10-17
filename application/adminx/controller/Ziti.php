<?php
namespace app\adminx\controller;
use think\Cache;

class Ziti extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			//$map['status'] = 1;
            $map['type'] = 0;
			$result = model('OrderBaoguo')->getList($map);			
			echo json_encode($result);
    	}else{
            $this->assign('type',config('BAOGUO_TYPE'));
	    	return view();
    	}
	}
}
?>