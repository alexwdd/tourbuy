<?php
namespace app\adminx\controller;

class Express extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Express')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('Express')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Express')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}
			}
			$this->assign('list', $list);
			$this->assign('expressModel',config('expressModel'));
			return view();
		}
	}

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Express')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>