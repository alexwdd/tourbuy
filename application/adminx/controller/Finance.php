<?php
namespace app\adminx\controller;

class Finance extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
            $typeArr= config('moneyType');
			$result = model('Finance')->getList();
			echo json_encode($result);
    	}else{
            $this->assign('type',config('moneyType'));
	    	return view();
    	}
	}

	#删除
    public function del() {
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('Finance')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }
}
?>