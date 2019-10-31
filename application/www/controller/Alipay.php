<?php
namespace app\www\controller;

class Alipay extends Common {

	public function _initialize(){
        parent::_initialize();
    }

	public function index(){
		if($this->is_weixin){
            return view();
        }else{
            $order_no = input('param.out_trade_no');

            $map['payNo'] = $order_no;
            $map['status'] = 0;
            $list = db("PayOrder")->where($map)->find();
            if(!$list){
                $this->error('订单不存在');
            }

            $rate = $this->getRate();
            $rmb = round($list['money']*$this->rate,1);

            $order['money'] = $list['money'];
            $order['out_trade_no'] = $order_no;
            $result = $this->getAlipayUrl($order);
            echo '<script>window.location.href="'.$result.'"</script>';
        }
	}
}