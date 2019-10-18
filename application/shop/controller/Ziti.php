<?php
namespace app\shop\controller;
use think\Cache;

class Ziti extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			//$map['status'] = 1;
            $map['type'] = 0;
            $map['shopID'] = $this->admin['id'];
			$result = model('OrderBaoguo')->getList($map);			
			echo json_encode($result);
    	}else{
            $this->assign('type',config('BAOGUO_TYPE'));
	    	return view();
    	}
	}

	public function hexiao(){
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要取消的数据');
		}else{
            foreach ($id as $key => $value) {
            	unset($map);
            	$map['id'] = $value;
            	$map['status'] = 1;
            	$map['shopID'] = $this->admin['id'];
            	$map['type'] = 0;
            	$list = db("OrderBaoguo")->where($map)->find(); 
            	if($list && $list['hexiao']==0){
            		db("OrderBaoguo")->where('id',$value)->update(['hexiao'=>1,'updateTime'=>time()]);
            		
            		$count = db("OrderBaoguo")->where(['orderID'=>$list['orderID'],'hexiao'=>1])->count();
            		$count1 = db("OrderBaoguo")->where(['orderID'=>$list['orderID']])->count();
            		if($count==$count1){
            			db("Order")->where('id',$list['orderID'])->setField("status",2);
            		}
            	}
            }
			$this->success("操作成功");
		}
	}
}
?>