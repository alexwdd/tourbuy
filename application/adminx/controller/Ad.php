<?php
namespace app\adminx\controller;

class Ad extends Admin {

	public $modelID=6;
	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Ad')->getList();
			$map['model']=$this->modelID;
			$cateArr = db('Category')->where($map)->column('id,name');
			foreach ($result['data'] as $key => $value) {
				if (isset($cateArr[$value['cid']])) {
					$result['data'][$key]['cate'] = $cateArr[$value['cid']];
				}                
            }
			echo json_encode($result);
    	}else{
    		$cate = model("Category")->getCate($this->modelID);
			foreach ($cate as $key => $value) {
				$count = count(explode('-', $value['path'])) - 3;
				$cate[$key]['count'] = $count;
			}
			$this->assign('cate', $cate);
	    	return view();
    	}
	}	

	#编辑
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('Ad')->saveData( $data );
		}else{
			$cate = model("Category")->getCate($this->modelID);
			foreach ($cate as $key => $value) {
				$count = count(explode('-', $value['path'])) - 3;
				$cate[$key]['count'] = $count;
			}
			$this->assign('cate', $cate);

			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Ad')->find($id);
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
			if(model('Ad')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>