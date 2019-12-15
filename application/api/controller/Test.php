<?php
namespace app\api\controller;

class Test extends Common{

    public function index(){
    	$list = db("GoodsSpecPrice")->select();
    	foreach ($list as $key => $value) {
            $inprice = round(($value['jiesuan']*(100-$value['servePrice']))/100,2);
    		$ztInprice = round(($value['jiesuan']*(100-$value['ztServePrice']))/100,2);
            db("GoodsSpecPrice")->where('item_id',$value['item_id'])->update([
                'inprice'=>$inprice,
                'ztInprice'=>$ztInprice,
            ]);
    	}
    }
}