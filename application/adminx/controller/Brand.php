<?php
namespace app\adminx\controller;

class Brand extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Brand')->getList();
			$map['cate']=4;	
            $cateArr = db('OptionItem')->where($map)->column('id,name');
            foreach ($result['data'] as $key => $value) {
                if (isset($cateArr[$value['cid']])) {
                    $result['data'][$key]['cate'] = $cateArr[$value['cid']];
                }                
            }
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('Brand')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Brand')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}
			}
			$this->assign('list', $list);

			$map['cate']=4;
            $cate = db('OptionItem')->field("id,name")->where($map)->order('value asc')->select();
            $this->assign('cate', $cate);
			return view();
		}
	}

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Brand')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>