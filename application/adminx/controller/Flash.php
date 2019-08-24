<?php
namespace app\adminx\controller;

class Flash extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Flash')->getList();
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	#添加
    public function pub() {
        if(request()->isPost()){
            $data = input('post.');
            db('Flash')->where('goodsID',$data['goodsID'])->delete();
            $result = model('Flash')->add( $data );
            return $result;
        }else{
            $id = input('get.id');

            $list = model('Goods')->find($id);
            if (!$list) {
                $this->error('信息不存在');
            }

            //参数规格
            $spec = db("GoodsSpecPrice")->where('goods_id',$id)->select();
            $this->assign('spec',$spec);

            //套餐
            $pack = db("Goods")->where('fid',$id)->select();
            $this->assign('pack',$pack);
           
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
			if(model('Flash')->del($id)){
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}
}
?>