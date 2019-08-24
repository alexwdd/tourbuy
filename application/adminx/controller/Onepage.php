<?php
namespace app\adminx\controller;

class Onepage extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Onepage')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#编辑
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('Onepage')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Onepage')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}
			}
			$this->assign('list', $list);
			return view();
		}
	}

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Onepage')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>