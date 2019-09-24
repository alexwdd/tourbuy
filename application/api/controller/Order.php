<?php
namespace app\api\controller;

class Order extends Auth {

    //我的订单
    public function lists(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $keyword = input('param.keyword');
            $type = input('param.type',0);
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            if($keyword!=''){
                $map['order_no'] = $keyword;
            }
            switch ($type) {
                case 1:
                    $map['status'] = 0;
                    break;
                case 2:
                    $map['status'] = 1;   
                    break;
                case 3:
                    $map['status'] = 2;   
                    break;
                case 4:
                    $map['status'] = 3;   
                    break;
                case 5:
                    $map['status'] = 99;   
                    break;
            }
            $config = tpCache('member');
            $map['memberID'] = $this->user['id'];
            $map['hide'] = 0;
            $obj = db('Order');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->field('id,order_no,total,front,back,sn,status,createTime')->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            foreach ($list as $key => $value) {
                $list[$key]['createTime'] = date("Y-m-d H:i:s",$value['createTime']);
                if($value['sn']=='' || $value['front']=='' || $value['back']==''){
                    $list[$key]['upload'] = 0;
                }else{
                    $list[$key]['upload'] = 1;
                }
                
                $goods = db("OrderCart")->field('goodsID,name,picname,price,number,spec')->where('orderID',$value['id'])->select();
                foreach ($goods as $k => $val) {
                    $goods[$k]['picname'] = getRealUrl($val['picname']);
                }
                $list[$key]['goods'] = $goods;
            }
            returnJson(1,'success',['next'=>$next,'data'=>$list]);
        }
    }

    //订单详情
    public function detail(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache('member');

            $id = input('post.id');
            if ($id=='') {
                returnJson(0,'参数错误');
            }
            $map['id'] = $id;
            $map['hide'] = 0;
            $map['memberID'] = $this->user['id'];
            $list = db('Order')->field('id,total,goodsMoney,discount,wallet,money,payment,point,order_no,name,tel,province,city,county,addressDetail,sn,front,back,sender,senderTel,intr,status,createTime')->where( $map )->find();
            if ($list) {                
                if($list['sn']=='' || $list['front']=='' || $list['back']==''){
                    $list['upload'] = 0;
                }else{
                    $list['upload'] = 1;
                }
                $list['front']=getRealUrl($list['front']);
                $list['back']=getRealUrl($list['back']);
                $list['statusStr'] = getOrderStatus($list);

                $goods = db("OrderCart")->field('goodsID,name,picname,price,number,spec')->where('orderID',$list['id'])->select();

                foreach ($goods as $k => $val) { 
                    $val['picname'] = getThumb($val['picname'],200,200);                    
                    $goods[$k]['picname'] = getRealUrl($val['picname']);
                }

                $list['createTime'] = date("Y-m-d H:i:s",$list['createTime']);
                $list['goods'] = $goods;

                $baoguo = db("OrderBaoguo")->field('id,payment,weight,kuaidi,kdNo,eimg,image')->where('orderID',$list['id'])->select();
                foreach ($baoguo as $key => $value) {
                    $goods = db("OrderDetail")->field('name,number')->where('baoguoID',$value['id'])->select();
                    $number = 0;
                    foreach ($goods as $k => $val) {
                        $number += $val['number'];
                    }
                    $baoguo[$key]['goods'] = $goods;
                    $baoguo[$key]['number'] = $number;

                    if($value['image']){
                        $image = explode(",", $value['image']);
                        foreach ($image as $k => $val) {
                            $image[$k] = getRealUrl($val);
                        }
                        $baoguo[$key]['image'] = $image;
                    }
                    if($value['eimg']){
                        $eimg = explode(",", $value['eimg']);
                        foreach ($eimg as $k => $val) {
                            $eimg[$k] = getRealUrl($val);
                        }
                        $baoguo[$key]['eimg'] = $eimg;
                    }
                }   
                returnJson(1,'success',[
                    'order'=>$list,
                    'baoguo'=>$baoguo
                ]); 
            }else{
                returnJson(0,'信息不存在');
            }
        }
    }

    //发布
    public function pub(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache("member");

            /*$senderID = input('post.senderID');
            $sender = db("Sender")->where(['id'=>$senderID,'memberID'=>$this->user['id']])->find();
            if(!$sender){
                returnJson(0,'发件人错误');
            }*/

            $addressID = input('post.addressID');
            $address = db("Address")->where(['id'=>$addressID,'memberID'=>$this->user['id']])->find();
            if(!$address){
                returnJson(0,'收件人错误');
            }

            $ids = input('post.ids');
            $couponIds = input('post.couponID');
            $intr = input('post.intr');
            $quhuoType = input('post.quhuoType');

            if($couponIds){
                $intr = explode("##",$intr);
            }
            if($couponIds){
                $couponIds = explode(",",$couponIds);
            }
            if($quhuoType){
                $quhuoType = explode(",",$quhuoType);
            }
            if ($ids=='') {
                returnJson(0,'缺少参数');
            }

            $map['memberID'] = $this->user['id'];
            $ids = explode(",",$ids);
            $map['id'] = array('in',$ids);
            $shopIds = db('Cart')->where($map)->group('shopID')->column('shopID');
            if (!$shopIds) {
                returnJson(0,'购物车中没有商品');
            }
            $shop = db("Shop")->field('id,name,tel')->whereIn('id',$shopIds)->select();
            $orders = [];
            foreach ($shop as $key => $value) {
                unset($map);
                $map['shopID'] = $value['id'];
                $map['memberID'] = $this->user['id'];
                $shopGoods = db("Cart")->where($map)->select();
                if($couponIds){
                    $couponID = $this->getCouponID($couponIds,$value['id']);
                }     
                $result = $this->getShopOrder($shopGoods,$value,$address,$couponID);
                $result['quhuoType'] = $quhuoType[$key];
                $result['intr'] = $intr[$key];
                $result['shopID'] = $value['id'];
                $result['shopName'] = $value['name'];
                $result['shopTel'] = $value['tel'];
                //保存订单
                $order_no = $this->saveShopOrder($address,$result);
                array_push($orders,$order_no);
            }
            returnJson(1,'订单创建成功',['order_no'=>implode(",",$orders)]);
        }
    }

    public function saveShopOrder($address,$orderData){
        unset($data);
        $order_no = $this->getOrderNo();
        $data['shopID'] = $orderData['shopID'];
        $data['memberID'] = $this->user['id'];
        $data['couponID'] = $orderData['coupon']['id'];
        $data['discount'] = $orderData['coupon']['discount'];
        $data['total'] = $orderData['total'];
        $data['point'] = $orderData['point'];
        $data['bonus'] = $orderData['bonus'];
        $data['payment'] = $orderData['baoguo']['totalPrice'];
        $data['goodsMoney'] = $orderData['goodsMoney'];
        $data['inprice'] = $orderData['inprice'];
        $data['order_no'] = $order_no;
        $data['addressID'] = $address['id'];
        $data['name'] = $address['name'];
        $data['tel'] = $address['tel'];
        $data['sn'] = $address['sn'];
        $data['front'] = $address['front'];
        $data['back'] = $address['back'];
        $data['province'] = $address['province'];
        $data['city'] = $address['city'];
        $data['county'] = $address['county'];
        $data['addressDetail'] = $address['addressDetail'];
        $data['sender'] = $orderData['shopName'];
        $data['senderTel'] = $orderData['shopTel'];
        $data['intr'] = $orderData['intr'];   
        $data['quhuoType'] = $orderData['quhuoType'];
        $data['createTime'] = time();    
        $data['status'] = 0;
        $data['payType'] = 0;
        $data['payStatus'] = 0;
        $orderID = db('Order')->insertGetId( $data );
        if ($orderID) {
            if($data['quhuoType']==0){
                foreach ($orderData['baoguo']['baoguo'] as $key => $value) {
                    //保存详单
                    $detail['orderID'] = $orderID;
                    $detail['shopID'] = $data['shopID'];
                    $detail['order_no'] = $data['order_no'];
                    $detail['memberID'] = $this->user['id'];  
                    $detail['payment'] = $value['yunfei'];
                    $detail['wuliuInprice'] = $value['inprice'];//物流成本
                    $detail['type'] = $value['type'];
                    $detail['weight'] = $value['totalWuliuWeight'];
                    $detail['kuaidi'] = $value['kuaidi'];
                    $detail['kdNo'] = '';
                    $detail['name'] = $data['name'];
                    $detail['tel'] = $data['tel'];
                    $detail['province'] = $data['province'];            
                    $detail['city'] = $data['city'];
                    $detail['county'] = $data['county'];
                    $detail['addressDetail'] = $data['addressDetail'];
                    $detail['sender'] = $data['sender'];
                    $detail['senderTel'] = $data['senderTel'];
                    $detail['createTime'] = time();          
                    $detail['status'] = 0;              
                    $detail['snStatus'] = 0;
                    $baoguoID = db('OrderBaoguo')->insertGetId($detail);
                    if ($baoguoID) {
                        foreach ($value['goods'] as $k => $val) {   
                            $gData = [
                                'orderID'=>$orderID,
                                'memberID'=>$this->user['id'],
                                'baoguoID'=>$baoguoID,
                                'goodsID'=>$val['goodsID'],
                                'specID'=>$val['specID'],
                                'name'=>$val['name'],
                                'short'=>$val['short'],
                                'number'=>$val['trueNumber'],    
                                'price'=>$val['price'],    
                                'createTime'=>time()
                            ];
                            db('OrderDetail')->insert($gData);      
                        }
                    }
                    unset($detail);
                }
            }

            //作废优惠券
            if($orderData['coupon']['id']>0){
                db("CouponLog")->where('id',$orderData['coupon']['id'])->update([
                    'useTime'=>time(),
                    'status'=>1
                ]);
            }

            //删除购物车，保存订单记录                
            $history = [];
            $ids = [];
            foreach ($orderData['cart'] as $key => $value) {
                array_push($history,[
                    'orderID'=>$orderID,
                    'memberID'=>$this->user['id'],
                    'goodsID'=>$value['goodsID'],
                    'fid'=>$value['fid'],
                    'specID'=>$value['specID'],
                    'name'=>$value['name'],
                    'picname'=>$value['picname'],
                    'price'=>$value['price'],
                    'spec'=>$value['spec'],
                    'number'=>$value['number'],
                    'trueNumber'=>$value['trueNumber']
                ]);
                array_push($ids,$value['id']);
            }
            db("OrderCart")->insertAll($history);

            unset($map);
            $map['memberID'] = $this->user['id'];
            $map['id'] = array('in',$ids);
            db("Cart")->where($map)->delete();
            return $order_no;
        }else{
            returnJson(0,'订单提交失败');
        }
    }

    public function getShopOrder($cart,$shop,$address,$couponID=null){
        $goodsMoney = 0;
        $inprice = 0;
        $point = 0;
        $bonus = 0;
        foreach ($cart as $key => $value) {
            $goods = db("Goods")->where('id',$value['goodsID'])->find();

            if (!$goods) {
                returnJson(0,'商品【'.$goods['name'].'】已经下架');
            }

            if ($goods['stock'] < $value['trueNumber']) {
                returnJson(0,'商品【'.$goods['name'].'】库存不足，当前库存为'.$goods['stock']);
            }

            $result = $this->getGoodsPrice($goods,$value['specID'],$this->flash);
            $cart[$key]['name'] = $goods['name'];
            $cart[$key]['say'] = $goods['say'];
            $cart[$key]['fid'] = $goods['fid'];
            $cart[$key]['picname'] = getRealUrl($goods['picname']);
            $cart[$key]['price'] = $result['price'];
            if($result['spec']){
                $cart[$key]['spec'] = $result['spec']['key_name'];
            }else{
                $cart[$key]['spec'] = '';
            }
            $cart[$key]['total'] = $result['price'] * $value['number'];  

            $goodsMoney += $cart[$key]['total'];
            $inprice += $goods['inprice'] * $value['trueNumber'];
            $point += $goods['point'] * $value['trueNumber'];
            $bonus += $goods['tjPoint'] * $value['trueNumber'];
        }

        $baoguo = $this->getYunfeiJson($cart,$address['province']);

        //判断优惠券
        if ($couponID>0 && is_numeric($couponID)) {
            $map['id'] = $couponID;
            $map['useTime'] = 0;
            $map['memberID'] = $this->user['id'];
            $map['endTime'] = array('gt',time());
            $coupon = db("CouponLog")->where($map)->find();
            if(!$coupon){
                returnJson(0,'无效的优惠券');
            }

            if(!$this->checkCoupon($coupon,$cart,$goodsMoney)){
                returnJson(0,'该优惠券不满足使用条件');
            }

            $discount = $coupon['dec'];
        }else{
            $couponID = 0;
            $discount = 0;
        }

        $total = $goodsMoney + $baoguo['totalPrice'];
        if($discount>0){
            $total = $total - $discount;
        }
        if($total<=0){
            $total = 1;
        }
        return ['cart'=>$cart,'baoguo'=>$baoguo,'goodsMoney'=>$goodsMoney,'inprice'=>$inprice,'point'=>$point,'bonus'=>$bonus,'total'=>$total,'coupon'=>['id'=>$couponID,'discount'=>$discount]];
    }

    //删除
    public function del(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $id = input('post.id');
            if ($id=='' || !is_numeric($id)) {
                returnJson(0,'缺少参数');
            }
            $map['id'] = $id;
            $map['memberID'] = $this->user['id'];
            $map['payStatus'] = 0;
            $list = db('Order')->where($map)->find();
            if ($list) {
                if ($list['wallet']>0) {
                    $fina = $this->getUserMoney($this->user['id']);
                    $fdata = array(
                        'type' => 3,
                        'money' => $list['wallet'],
                        'memberID' => $this->user['id'],
                        'doID' => $this->user['id'],
                        'oldMoney'=>$fina['money'],
                        'newMoney'=>$fina['money']+$list['wallet'],
                        'admin' => 2,
                        'msg' => '取消订单，退还账户余额$'.$list['wallet'].'，订单号：'.$list['order_no'],
                        'createTime' => time()
                    );
                    db('Finance')->insert($fdata);
                }
                db('Order')->where('id',$id)->delete();
                db('OrderBaoguo')->where('orderID',$id)->delete();
                db('OrderCart')->where('orderID',$id)->delete();
                db('OrderDetail')->where('orderID',$id)->delete();
            }
            returnJson(1,'success');
        }       
    }

    //删除
    public function hide(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $id = input('post.id');
            if ($id=='' || !is_numeric($id)) {
                returnJson(0,'缺少参数');
            }
            $map['id'] = $id;
            $map['memberID'] = $this->user['id'];
            $map['status'] = array('in',[3,99]);
            $list = db('Order')->where($map)->find();
            if ($list) {
                db('Order')->where('id',$id)->setField('hide',1);
            }
            returnJson(1,'success');
        }       
    }

    
    public function pay(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $order_no = input('post.order_no');
            if($order_no==''){
                returnJson(0,'参数错误');
            }
            $order_no = explode(',',$order_no);

            $map['order_no'] = array('in',$order_no);
            $map['memberID'] = $this->user['id'];
            $map['status'] = 0;
            $list = db("Order")->field('id,order_no,total,money')->where($map)->select();
            if(!$list){
                returnJson(0,'订单不存在');
            }

            $total = 0;
            foreach ($list as $key => $value) {
                $total += $value['total'];
            }

            $rate = $this->getRate();
            $rmb = round($total*$this->rate,1);
            /*$fina = $this->getUserMoney($this->user['id']);

            if($fina['money']>=$total){
                $type = 2;
            }else{
                $type = 1;
            }*/
            returnJson(1,'success',[
                'data'=>$list,
                //'wallet'=>$fina,
                'info'=>[
                    //'type'=>$type,
                    'rate'=>$rate,
                    'rmb'=>$rmb,
                    'total'=>$total,
                ]                
            ]);
        }
    }

    public function doPay(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $order_no = input('post.order_no');
            $payType = input('post.type');

            $order_no = explode(',',$order_no);

            $map['order_no'] = array('in',$order_no);
            $map['memberID'] = $this->user['id'];
            $map['status'] = 0;
            $list = db("Order")->field('id,order_no,total,money')->where($map)->select();
            if(!$list){
                returnJson(0,'订单不存在');
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
            if($payType==1){                
                $url = 'http://'.$_SERVER['HTTP_HOST'].url('www/alipay/index','out_trade_no='.$out_trade_no);
                returnJson(1,'success',['url'=>$url]);
            }elseif($payType==2){
                $result = $this->getWeixinUrl($order);
                if($result['result']=='SUCCESS'){
                    returnJson(1,'success',['url'=>$result['QRCodeURL']]);
                }else{
                    returnJson(0,'发起支付失败');
                }
            }else{
                returnJson(0,'支付方式错误');
            }                     
        }
    }    

    public function success(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $order_no = input('post.order_no');
            $map['order_no'] = $order_no;            
            $map['memberID'] = $this->user['id'];
            $order = db('Order')->field('id,order_no,fund,point,payStatus')->where($map)->find();
            if(!$order){
                returnJson(0,'订单不存在');
            }

            //为您推荐 
            $obj = db('GoodsPush');
            $list = $obj->field('goodsID')->where('cateID',3)->limit(10)->order('id desc')->select();
            foreach ($list as $key => $value) {                
                $goods = db("Goods")->field('id,name,picname,price,say,marketPrice,comm,empty,tehui,flash,baoyou')->where('id',$value['goodsID'])->find();   

                unset($list[$key]['goodsID']);
                $goods['picname'] = getThumb($goods["picname"],200,200);
                $goods['picname'] = getRealUrl($goods['picname']);
                $goods['rmb'] = round($goods['price']*$this->rate,1);
                $list[$key] = $goods;
            }

            returnJson(1,'success',['data'=>$order,'goods'=>$list]);
        }
    }

    public function updatePersonCard(){
        if (request()->isPost()) {
            //if(!checkFormDate()){returnJson(0,'ERROR');}

            $id = input('post.id');
            $front = input('post.front');
            $back = input('post.back');
            if ($id=='') {
                returnJson(0,'参数错误');
            }
            if ($front=='' && $back=='') {
                returnJson(0,'请选择身份证照片');
            }

            $map['id'] = $id;
            $map['memberID'] = $this->user['id'];
            $list = db("Order")->where($map)->find();
            if(!$list){
                returnJson(0,'订单不存在');
            }

            if($front!='' && strstr($front, 'base64')){
                $path = config('UPLOAD_PATH').'sn/'.$this->user['id'].'/';
                $fileName = createNonceStr();
                $frontUrl = $this->base64ToImg($path,$fileName,$front);
            }else{
                $frontUrl = $front;
            }
            
            if($back!='' && strstr($back, 'base64')){
                $path = config('UPLOAD_PATH').'sn/'.$this->user['id'].'/';
                $fileName = createNonceStr();
                $backUrl = $this->base64ToImg($path,$fileName,$back);
            }else{
                $backUrl = $back;
            }
            
            if($frontUrl != ''){
                $data['front'] = $frontUrl;
            }

            if($backUrl != ''){
                $data['back'] = $backUrl;
            }

            $res = db("Order")->where('id',$id)->update($data);

            if($res){
                $frontUrl = getRealUrl($frontUrl);
                $backUrl = getRealUrl($backUrl);
                returnJson(1,'success',['front'=>$frontUrl,'back'=>$backUrl]);
            }else{
                returnJson(0,'照片保存失败');
            }
        }
    }
}