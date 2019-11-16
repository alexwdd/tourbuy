<?php
namespace app\adminx\controller;

class City extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('City')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
	public function pub() {
		if(request()->isPost()){
	        $data = input('post.');
	        return model('City')->saveData( $data );
		}else{
			$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('City')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}
				$expID = db("CityExpress")->where('cityID',$list['id'])->column('expressID');
			}
			$this->assign('expID', $expID);
			$this->assign('list', $list);

			$express = db("Express")->select();
			if ($list) {	
				foreach ($express as $key => $value) {
					unset($map);
					$map['cityID'] = $list['id'];
					$map['expressID'] = $value['id'];	
					$express[$key]['address'] = db("CityExpress")->where($map)->value('address');
				}				
			}else{
				foreach ($express as $key => $value) {
					$express[$key]['address'] = '';
				}
			}
			$this->assign('express',$express);
			return view();
		}
	}

	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('City')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>