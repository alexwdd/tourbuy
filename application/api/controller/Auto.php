<?php
namespace app\api\controller;

use app\common\controller\Base;

class Auto extends Base {

    public function _initialize(){
        parent::_initialize();        
    }

    public function test(){
        $order = db("Order")->where('id',36)->find();
        $this->saveJiangjin($order);
        //$this->uploadPxPerson($order);
    }

    //删除未付款的订单
    public function delete(){
        $config = tpCache('member');
        $map['payStatus'] = 0;
        $map['wallet'] = 0;
        $map['createTime'] = array('lt',time()-$config['orderTime']*3600);
        $list = db("Order")->where($map)->select();
        foreach ($list as $key => $value) {
            db("Order")->where('id',$value['id'])->delete();
            db("OrderBaoguo")->where('orderID',$value['id'])->delete();
            db("OrderCart")->where('orderID',$value['id'])->delete();
            db("OrderDetail")->where('orderID',$value['id'])->delete();
        }
    }

    //创建运单
    public function createOrder(){
        $map['kdNo'] = '';
        $map['expressID'] = array('gt',0);
        //$map['type'] = array('not in',[15,16,17]);
        $list = db("OrderBaoguo")->where($map)->select();
        foreach ($list as $key => $value) {
            if($value['expressID']==4){
                $this->createEweOrder($value);
            }
            if($value['expressID']==5){
                $this->createPxOrder($value);
            }
        }
    }

    //上传身份证
    public function uploadPersonPhoto(){
        $map['kdNo'] = array('neq','');
        $map['snStatus'] = 0;
        $list = db("OrderBaoguo")->where($map)->select();
        foreach ($list as $key => $value) {
            if($value['expressID']==4){
                $this->uploadEwePerson($value);
            }
            if($value['expressID']==5){
                $this->uploadPxPerson($value);
            }
        }
    }
}