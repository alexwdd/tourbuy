<?php
namespace app\adminx\controller;

class Shop extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Shop')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('Shop')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Shop')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}
				if ($list['image']) {
                    $image = explode(",", $list['image']);
                } else {
                    $image = [];
                }
                $this->assign('image', $image);

                if ($list['cate']) {
                    $cateIds = explode(",", $list['cate']);
                } else {
                    $cateIds = [];
                }
                $this->assign('cateIds', $cateIds);
			}else{
				$list['status'] = 1;
			}
			$this->assign('list', $list);

			$city = db("City")->select();
			$this->assign('city', $city);

			$cate = db("GoodsCate")->where('fid',0)->order('sort asc , id asc')->select();
			$this->assign('cate',$cate);
			return view();
		}
	}

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Shop')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>