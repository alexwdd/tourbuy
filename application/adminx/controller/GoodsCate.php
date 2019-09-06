<?php
namespace app\adminx\controller;

class GoodsCate extends Admin {
    public function index(){
		$list = db("GoodsCate")->where('fid',0)->order('sort asc,id asc')->select();
		foreach ($list as $key => $value) {
			$child = db("GoodsCate")->where('fid',$value['id'])->order('sort asc,id asc')->select();
			foreach ($child as $k => $val) {				
				$sun = db("GoodsCate")->where('fid',$val['id'])->order('sort asc,id asc')->select();
				$child[$k]['child'] = $sun;
			}
			$list[$key]['child'] = $child;
        }
		$this->assign('list', $list);
    	return view();
    }

	public function pub(){
		if(request()->isPost()){
			$data = input('post.');
			if($data['id']!=''){
				if ($data['id'] == $data['fid']) {
					$this->error("不能以自身为上级分类");
				}
			}
			
			if ($data['fid']>0) {
				$father = db("GoodsCate")->where('id',$data['fid'])->find();
				if (!$father) {
					$this->error("上级分类不存在");
				}
				$data['path'] = $father['path'];
			}else{
				$data['path'] = '0-';
			}
	        return model('GoodsCate')->saveData($data);
		}else{
			$id = input('param.id');
			$fid = input('param.fid');

			$cate = db('GoodsCate')->field("id,name,fid,path")->order('path')->select();
			foreach ($cate as $key => $value) {
				$count = count(explode('-', $value['path'])) - 3;
				$cate[$key]['count'] = $count;
			}
			$this->assign('cate', $cate);
			
			if ($id!='' || is_numeric($id)) {
				$list=db('GoodsCate')->where('id='.$id)->find();
				if(!$list){
					$this->error('没有该分类');
				}
			}
			$this->assign('list',$list);
			$this->assign('fid',$fid);
			return view();
		}
	}
	
	public function del(){
		if (request()->isPost()){
			$id = input('post.id');
			if(!isset($id) || !is_numeric($id)){
				$this->error('您没有选择任何分类！');
			}
			$cate = db('GoodsCate');
			$list = $cate->where('fid='.$id)->find();
			if($list){
				$this->error('请先删除子栏目');
			}
			$list = $cate->where('id='.$id)->delete();		
			if($list){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}		
	}   
}