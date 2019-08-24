<?php
namespace app\adminx\controller;

class Node extends Admin {

	#列表
	public function index() {
		$node = db('Node')->where('level=1')->order('sort asc , id asc')->select();
		foreach ($node as $key => $value) {
			$action = db('Node')->where('pid='.$value['id'])->select();
			$node[$key]['child'] = $action;
		}
		$this->assign('node',$node);
		return view();

	}

	#获得子节点
	public function getchild(){
		$pid = input('param.pid');
		$map['pid'] = $pid;
		$list = db('Node')->field('id,name,value')->where($map)->order('sort asc')->select();
		echo json_encode(['code'=>1,'data'=>$list]);
	}

	#添加模型
	public function pub() {
		if(request()->isPost()) {
			$data = input('post.');
	        return model('Node')->saveData( $data );
		}else{
			$level = input('level');
			$id = input('id');
			$pid = input('pid');
			if ($pid>0) {
				$pname = db('Node')->where('id',$pid)->value('name');
				if (!$pname) {
					$this->error("父节点不存在");
				}
			}
			if ($id>0) {
				$list = db('Node')->where('id',$id)->find();
				if (!$list) {
					$this->error("节点不存在");
				}
			}else{
				$list['status'] = 1;
				$list['display'] = 0;
			}
			$this->assign('list',$list);		
			$this->assign('level',$level);
			$this->assign('pname',$pname);
			$this->assign('pid',$pid);
			return view();
		}		
	}

	#删除
	public function del() {
		if(request()->isPost()){
			$id = input('param.id');
			if(!isset($id)){
				$this->error("请选择删除的节点");
			}
			$obj = db('Node');
			$list = $obj->where('pid='.$id)->find();
			if($list){
				$this->error("请先删除子节点");
			}
			$list = $obj->where('id='.$id)->delete();
			if($list){
				$this->success("操作成功");
			}else{
				$this->error("删除失败");
			}
		}		
	}
	
}
?>