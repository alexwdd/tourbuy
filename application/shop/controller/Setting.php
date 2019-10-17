<?php 
namespace app\shop\controller;

class Setting extends Admin{
    #列表
    public function index(){ 
        if (request()->isPost()) {
        	$data = input('post.');
        	$data['id'] = $this->admin['id'];
	        return model('Shop')->saveData( $data );
        }else{
        	$list = db('Shop')->where('id',$this->admin['id'])->find();

            $list['cityName'] = db("City")->where('id',$list['cityID'])->value("name");

            $shopCate = db("GoodsCate")->where('id','in',$list['cate'])->column("name");
            $list['shopCate'] = implode(",",$shopCate);

        	if ($list['image']) {
                $image = explode(",", $list['image']);
            } else {
                $image = [];
            }
            $this->assign('image', $image);
        	$this->assign('list',$list);

        	if ($list['cate']) {
                $cateIds = explode(",", $list['cate']);
            } else {
                $cateIds = [];
            }
            $this->assign('cateIds', $cateIds);

        	$cate = db("GoodsCate")->where('fid',0)->order('sort asc , id asc')->select();
			$this->assign('cate',$cate);
            return view();
        }       
    }
}
?>