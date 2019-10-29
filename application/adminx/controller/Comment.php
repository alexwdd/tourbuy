<?php
namespace app\adminx\controller;

class Comment extends Admin {

	public function index(){
        if (request()->isPost()) {
            $result = model('GoodsComment')->getList($map);
            echo json_encode($result);
        }else{
            return view();
        }
    }

    public function checkGoodsComment(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要取消的数据');
        }else{
            $map['id'] = array('in',$id);
            db("GoodsComment")->where($map)->setField('status',1);
            $this->success("操作成功");
        }
    }

    public function delGoodsComment(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('GoodsComment')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }

    public function shop(){
        if (request()->isPost()) {
            $result = model('ShopComment')->getList($map);
            echo json_encode($result);
        }else{
            return view();
        }
    }

    public function checkShopComment(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要取消的数据');
        }else{
            $map['id'] = array('in',$id);
            db("ShopComment")->where($map)->setField('status',1);
            $this->success("操作成功");
        }
    }

    public function delShopComment(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('ShopComment')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }
}
?>