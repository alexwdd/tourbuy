<?php
namespace app\adminx\controller;

class GoodsModel extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('GoodsModel')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');	        
	        return model('GoodsModel')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('GoodsModel')->find($id);
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
			if(model('GoodsModel')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}

	public function Spec(){
		if (request()->isPost()) {
			$result = model('ModelSpec')->getList();
			$cateArr = db('GoodsModel')->where($map)->column('id,name');
			foreach ($result['data'] as $key => $value) {
				if (isset($cateArr[$value['mID']])) {
					$result['data'][$key]['cate'] = $cateArr[$value['mID']];
				}
            } 
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	public function pubSpec() {
		if(request()->isPost()){
	        $data = input('post.');	        
	        return model('ModelSpec')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('ModelSpec')->find($id);
			}
			$model = db('GoodsModel')->select();
			$this->assign('model', $model);
			$this->assign('list', $list);
			return view();
		}
	}

	#删除
	public function delSpec() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('ModelSpec')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>