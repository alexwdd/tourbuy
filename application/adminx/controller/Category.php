<?php
namespace app\adminx\controller;

class Category extends Admin {
    public function index(){
    	$model=input("model",0,"intval");
		if(empty($model)){
			$model=1;
		}
		$map['model'] = $model;
		$list = db("Category")->where($map)->order('path,id asc')->select();
		foreach ($list as $key => $value) {
            $count = count(explode('-', $value['path'])) - 2;
            if ($value['fid'] > 0) {
                $list[$key]['style'] = 'style="padding-left:' . (($count * 10) + 10) . 'px;"';
            }
        }
		$this->assign('list', $list);
		$this->assign('model', $model);
		$this->assign('cateModel', config("TABLE_MODEL"));
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
				$father = db("Category")->where('id',$data['fid'])->find();
				if (!$father) {
					$this->error("上级分类不存在");
				}
				$data['path'] = $father['path'];
			}else{
				$data['path'] = '0-';
			}
	        return model('Category')->saveData($data);
		}else{
			$id = input('param.id');
			$fid = input('param.fid');
			$model = input('param.model');

			$map['model']=$model;
			$cate = db('Category')->where($map)->field("id,name,fid,path")->order('path')->select();
			foreach ($cate as $key => $value) {
				$count = count(explode('-', $value['path'])) - 3;
				$cate[$key]['count'] = $count;
			}
			$this->assign('cate', $cate);
			
			if ($id!='' || is_numeric($id)) {
				$list=db('Category')->where('id='.$id)->find();
				if(!$list){
					$this->error('没有该分类');
				}
			}
			$this->assign('list',$list);
			$this->assign('fid',$fid);
			$this->assign('model',$model);
			return view();
		}
	}
	
	public function del(){
		if (request()->isPost()){
			$id = input('post.id');
			if(!isset($id) || !is_numeric($id)){
				$this->error('您没有选择任何分类！');
			}
			$cate = db('Category');
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