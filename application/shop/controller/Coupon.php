<?php
namespace app\shop\controller;

class Coupon extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$result = model('Coupon')->getList();			
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

    public function pub(){
        if(request()->isPost()){
            $data = input('post.');
            return model('Coupon')->saveData( $data );
        }else{
        	$id = input('get.id');
			if ($id!='' || is_numeric($id)) {
				$list = model('Coupon')->find($id);
				if (!$list) {
					$this->error('信息不存在');
				}		
			}

            $shop = db("Shop")->field('id,name,cityID')->select();
            $this->assign('shop', $shop);

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
            if(model('Coupon')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }

    public function create(){
        if(request()->isPost()){
            $number = input('post.number');
            $couponID = input('post.couponID');
            $list = db('Coupon')->where('id',$couponID)->find();
            for($i=0; $i<$number; $i++){
                $data = [
                    'shopID'=>$list['shopID'],
                    'memberID'=>0,
                    'nickname'=>'',
                    'couponID'=>$couponID,
                    'code'=>$this->getCouponNo(),
                    'name'=>$list['name'],
                    'desc'=>$list['desc'],
                    'full'=>$list['full'],
                    'dec'=>$list['dec'],
                    'intr'=>$list['intr'],
                    'goodsID'=>$list['goodsID'],
                    'status'=>0,
                    'useTime'=>0,
                    'endTime'=>0,
                    'createTime'=>time(),
                ];
                db("CouponLog")->insert($data);
            }
            return info('操作成功',1);
        }else{
            $couponID = input('param.couponID');
            $list = model('Coupon')->find($couponID);
            if (!$list) {
                $this->error('信息不存在');
            }
            $this->assign('list', $list);
            return view();
        }        
    }

    public function log(){
        if (request()->isPost()) {
            $couponID = input('param.couponID');
            $map['couponID'] = $couponID;
            $result = model('CouponLog')->getList($map);
            echo json_encode($result);
        }else{
            $couponID = input('param.id');
            $this->assign('couponID',$couponID);
            return view();
        }
    }

    public function delLog(){
        $id = explode(",",input('post.id'));
        if (count($id)==0) {
            $this->error('请选择要删除的数据');
        }else{
            if(model('CouponLog')->del($id)){
                $this->success("操作成功");
            }else{
                $this->error('操作失败');
            }
        }
    }
}
?>