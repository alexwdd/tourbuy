<?php
namespace app\api\controller;

use app\common\controller\Base;

class Auto extends Base {

    public function _initialize(){
        parent::_initialize();        
    }

    public function test(){
        $list = db("Cart")->where('memberID',10002)->select();
        $result = $this->getYunfeiJson($list);
        dump($result);
    }

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
}