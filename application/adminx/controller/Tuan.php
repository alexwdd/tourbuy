<?php
namespace app\adminx\controller;

class Tuan extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			/*$result = model('Flash')->getList();
			echo json_encode($result);*/
    	}else{
	    	return view();
    	}
	}
}
?>