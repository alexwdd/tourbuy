<?php
namespace app\adminx\controller;

class Option extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('OptionCate')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('OptionCate')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('OptionCate')->find($id);
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
			if(model('OptionCate')->del($id)){
				$map['cate'] = array('in',$id);
				db("OptionItem")->where($map)->delete();
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}

	//查看
    public function item(){ 
        if (request()->isPost()) {
        	$cate = input('param.cate');
        	$map['cate'] = $cate;
			$result = model('OptionItem')->getList($map);
			echo json_encode($result);
    	}else{
    		$cate = input('param.cate');
	        if ($cate=='' || !is_numeric($cate)) {
	        	$this->error("参数错误");
	        }
	        $this->assign('cate',$cate);
	    	return view();
    	}       
    }

    #添加
	public function pubItem() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('OptionItem')->saveData( $data );
		}else{
			$id = input('param.id');
			$cate = input('param.cate');
			if ($id!='' || is_numeric($id)) {
				$list = model('OptionItem')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}		
			}
			$this->assign('list', $list);
			$this->assign('cate', $cate);
			return view();
		}
	}

	#删除
	public function delItem() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('OptionItem')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>