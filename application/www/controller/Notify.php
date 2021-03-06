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

        //file_put_contents("note".date("Y-m-d",time()).".txt", date ( "Y-m-d H:i:s" ) . "  "."通知ID:" .$notice_id."订单:".$merchant_trade_no."token:".$token. "\r\n", FILE_APPEND);

        if($merchant_trade_no==''){die;}        
        db('PayOrder')->where('payNo',$merchant_trade_no)->setField("status",1);
        $order_no = db('PayOrder')->where('payNo',$merchant_trade_no)->value("order_no");
        $order_no = explode(",", $order_no);
        echo 'success';
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

                    //发送邮件
                    $this->orderEmail($list);

                    //发送短信
                    $this->orderSms($list);

                    $this->saveJiangjin($list);             
                }
            }
        }
    }

    //polipay 异步通知
    public function poli(){
        $token = $_REQUEST["Token"];
        $token = '1111111';
        if($token==""){die;}

        file_put_contents("note".date("Y-m-d",time()).".txt", date ( "Y-m-d H:i:s" ) . "  "."通知token:".$token. "\r\n", FILE_APPEND);

        $config = tpCache("poli");
        $auth = base64_encode($config['POLI_ID'].":".$config['POLI_KEY']);
        $header = array();
        $header[] = 'Authorization: Basic '.$auth;
        $ch = curl_init("https://poliapi.apac.paywithpoli.com/api/v2/Transaction/GetTransaction?token=".urlencode($token));

        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER ,false);
        curl_setopt($ch,CURLOPT_CAPATH ,'https://raw.githubusercontent.com/bagder/ca-bundle/master/');
        curl_setopt( $ch, CURLOPT_CAINFO, "ca-bundle.crt");
        curl_setopt( $ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_POST, 0);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec( $ch );
        curl_close ($ch);
        $json = json_decode($response, true); 
        if($json['TransactionStatusCode'] == 'Completed' && $json['AmountPaid'] > 0){
            $merchant_trade_no = $json['MerchantReference'];

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
                        $data['payType'] = 4;
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

                        //发送邮件
                        $this->orderEmail($list);

                        //发送短信
                        $this->orderSms($list);

                        $this->saveJiangjin($list);
                    }
                }
            }
        }
    }

    public function orderSms($order){
        $shop = db("Shop")->where('id',$order['shopID'])->find();
        
        if(check_mobile($shop['masterTel'])){
            if($order['quhuoType']==0){
                $content = 'You have a new delivery order: '.$order['order_no'].'.  Please prepare goods and send to Warehouse.';
            }else{
                $content = 'You have a Pick-up order: '.$order['order_no'].'. Please prepare goods for customer collection.';
            }            
            au_sms($shop['masterTel'],$content);
        }        
    }

    public function test(){
        $order = db("Order")->order('id desc')->find();
        $this->orderEmail($order);
    }

    public function orderEmail($order){
        $shop = db("Shop")->where('id',$order['shopID'])->find();
        if($shop && $shop['masterEmail']!=''){
            $this->assign('order',$order);

            $goods = db("OrderCart")->where('orderID',$order['id'])->select();
            $total = 0;
            foreach ($goods as $key => $value) {
                $total += $value['jiesuan'] * $value['number'];
            }
            $this->assign('goods',$goods);
            $this->assign('total',$total);

            if($order['couponID']>0){
                $coupon = db("CouponLog")->where('id',$order['couponID'])->find();
                $this->assign('coupon',$coupon);
            }
            

            $baoguo = db('OrderBaoguo')->where('orderID',$order['id'])->select();
            foreach ($baoguo as $key => $value) {
                $baoguo[$key]['address'] = db("CityExpress")->where([
                    'expressID'=>$value['expressID'],
                    'cityID'=>$value['cityID']
                ])->value("address");

                $baoguo[$key]['goods'] = db("OrderDetail")->where('baoguoID',$value['id'])->select();
            }
            $this->assign('baoguo',$baoguo);
            $content = $this->fetch("email/order");
            $email = $shop['masterEmail'];
            $title = 'Hello '.$shop['name'].'! You have a new order from tourbuy';

            $data['title'] = $title;
            $data['content'] = $content;
            $data['shopID'] = $order['shopID'];
            $data['orderID'] = $order['id'];
            $data['createTime'] = time();
            $res = db("OrderEmail")->insert($data);
            if($res){
                sendEmail($email,$title,$content);
            }
        }        
    }
}