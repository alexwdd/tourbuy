<?php
namespace app\api\controller;

use app\common\controller\Base;

class Auto extends Base {

    public function _initialize(){
        parent::_initialize();        
    }

    public function test(){
        $order = db("OrderBaoguo")->where('id',31)->find();
        //$this->createEweOrder($order);
        $this->uploadEwePerson($order);
    }

    //删除未付款的订单
    public function delete(){
        $config = tpCache('member');
        $map['payStatus'] = 0;
        $map['wallet'] = 0;
        $list = db("Order")->where($map)->select();
        foreach ($list as $key => $value) {
            if($value['isCut']==1 && time()>($value['createTime']+$config['hour']*3600)){
                db("Order")->where('id',$value['id'])->setField('endTime',time());
            }

            if($value['endTime']>0 && time()>($value['endTime']+$config['orderTime']*3600)){
                db("Order")->where('id',$value['id'])->delete();
                db("OrderBaoguo")->where('orderID',$value['id'])->delete();
                db("OrderCart")->where('orderID',$value['id'])->delete();
                db("OrderDetail")->where('orderID',$value['id'])->delete();
                db("OrderCut")->where('orderID',$value['id'])->delete();
            }
        }
    }

    //创建运单
    public function createOrder(){
        $map['kdNo'] = '';
        $map['expressID'] = array('gt',0);
        //$map['type'] = array('not in',[15,16,17]);
        $list = db("OrderBaoguo")->where($map)->select();
        dump($list);die;
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