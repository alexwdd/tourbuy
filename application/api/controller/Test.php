<?php
namespace app\api\controller;

use app\common\controller\Base;

class Test extends Base {

    public function index(){
    	$orderID = 27;
    	$map['id'] = $orderID;
    	$order = db("Order")->where($map)->find();
    	$this->assign('order',$order);

    	$goods = db("OrderCart")->where('orderID',$orderID)->select();
    	$this->assign('goods',$goods);

    	if($order['couponID']>0){
	    	$coupon = db("CouponLog")->where('id',$order['couponID'])->find();
	    	$this->assign('coupon',$coupon);
    	}
    	

    	$baoguo = db('OrderBaoguo')->where('orderID',$orderID)->select();
    	foreach ($baoguo as $key => $value) {
    		$baoguo[$key]['address'] = db("CityExpress")->where([
    			'expressID'=>$value['expressID'],
    			'cityID'=>$value['cityID']
    		])->value("address");

    		$baoguo[$key]['goods'] = db("OrderDetail")->where('baoguoID',$value['id'])->select();
    	}
    	$this->assign('baoguo',$baoguo);

        $content = $this->fetch("email/notify");
        //echo $content;die;
        $email = 'pingshanguang@gmail.com';
        $title = 'Hello ABC! You have a new order from tourbuy';
        sendEmail($email,$title,$content);
    }

}