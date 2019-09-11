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

            $map['id'] = array('in',$order_no);
            $map['status'] = 0;
            $list = db("Order")->field('id,order_no,total,money')->where($map)->select();
            if(!$list){
                $this->error('订单不存在');
            }

            $total = 0;
            $out_trade_no = '';
            foreach ($list as $key => $value) {
                $total += $value['total'];
                if($out_trade_no==''){
                    $out_trade_no = $value['id'];
                }else{
                    $out_trade_no .= '_'.$value['id'];
                }
            }

            $rate = $this->getRate();
            $rmb = round($total*$this->rate,1);

            $order['money'] = $total;
            $order['out_trade_no'] = $out_trade_no;
            $result = $this->getAlipayUrl($order);
            echo '<script>window.location.href="'.$result.'"</script>';
        }
	}
}