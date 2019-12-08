<?php
namespace app\api\controller;

class Test extends Common{

    public function index(){
    	$list = db("OrderDetail")->select();
    	foreach ($list as $key => $value) {
    		$map['goodsID'] = $value['goodsID'];
    		$map['memberID'] = $value['memberID'];
    		$map['specID'] = $value['specID'];
    		$res = db("OrderCart")->where($map)->find();
    		if($res){
    			db("OrderDetail")->where('id',$value['id'])->setField("spec",$res['spec']);
    		}
    	}
    }
}