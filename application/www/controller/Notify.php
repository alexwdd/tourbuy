<?php
namespace app\www\controller;

use app\common\controller\Base;

class Notify extends Base {

	public function _initialize(){
        parent::_initialize();
    }

	public function index(){
        $notice_id = input('param.notice_id');
        $merchant_trade_no = input('param.merchant_trade_no');
        $token = input('param.token');
        file_put_contents("note".date("Y-m-d",time()).".txt", date ( "Y-m-d H:i:s" ) . "  "."通知ID:" .$notice_id."订单:".$merchant_trade_no."token:".$token. "\r\n", FILE_APPEND);

        db('PayOrder')->where('payNo',$merchant_trade_no)->setField("status",1);
        $order_no = db('PayOrder')->where('payNo',$merchant_trade_no)->value("order_no");
        $order_no = explode(",", $order_no);

        foreach ($order_no as $key => $value) {
            $map['order_no'] = $value;
            $list = db('Order')->where($map)->find();
            if ($list) {
                if ($list['payStatus'] == 0) {
                    //更新订单状态
                    $data['status'] = 1;
                    $data['payStatus'] = 1;
                    $data['updateTime'] = time();
                    db('Order')->where($map)->update($data);
                    db('OrderBaoguo')->where('orderID',$list['id'])->setField('status',1);

                    $detail = db("OrderCart")->where('orderID',$list['id'])->select();
                    foreach ($detail as $key => $val) {
                        unset($map);
                        if($val['fid']>0){
                            $map['id'] = $val['fid'];
                            $map['fid'] = $val['fid'];
                            db("Goods")->whereOr($map)->setDec("stock",$val['trueNumber']);
                        }else{
                            $map['id'] = $val['goodsID'];
                            db("Goods")->where($map)->setDec("stock",$val['trueNumber']);
                        }                           
                    } 
                    //发送短信

                    $this->saveJiangjin($list);             
                }
            }
        }
	}
}