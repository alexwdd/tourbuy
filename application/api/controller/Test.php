<?php
namespace app\api\controller;

class Test extends Common{

    public function index(){
    	$goods = db("Goods")->where('id',8)->find();
    	$result = $this->getGoodsPrice($goods,0,$this->flash);
    	dump($this->flash);
    	dump($result);
    }

}