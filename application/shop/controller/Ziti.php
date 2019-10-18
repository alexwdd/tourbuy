<?php
namespace app\shop\controller;
use think\Cache;

class Ziti extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			//$map['status'] = 1;
            $map['type'] = 0;
            $map['shopID'] = $this->admin['id'];
			$result = model('OrderBaoguo')->getList($map);			
			echo json_encode($result);
    	}else{
            $this->assign('type',config('BAOGUO_TYPE'));
	    	return view();
    	}
	}
}
?>