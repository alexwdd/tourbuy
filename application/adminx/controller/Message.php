<?php
namespace app\adminx\controller;

class Message extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Message')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#群发
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('Message')->batchPub( $data );
		}else{
			return view();
		}
	}

	//查看
    public function view(){
        $id = input('get.id');
        if ($id!='' || is_numeric($id)) {
            $list = model('Message')->find($id);
            if (!$list) {
                $this->error('信息不存在');
            }
        }
        $this->assign('list', $list);
        return view();
    }

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Message')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>