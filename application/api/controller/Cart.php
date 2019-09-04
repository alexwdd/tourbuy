<?php
namespace app\api\controller;

class Cart extends Auth {

    //我的购物车
    public function lists(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $map['memberID'] = $this->user['id'];
            $shopIds = db('Cart')->where($map)->group('shopID')->column('shopID');
            $shop = db("Shop")->field('id,name')->whereIn('id',$shopIds)->select();
            foreach ($shop as $key => $value) {
                unset($map);
                $map['shopID'] = $value['id'];
                $map['memberID'] = $this->user['id'];
                $shopGoods = db("Cart")->where($map)->select();

                foreach ($shopGoods as $k => $val) {
                    $goods = db("Goods")->where('id',$val['goodsID'])->find();
                    $result = $this->getGoodsPrice($goods,$val['specID'],$this->flash);

                    $shopGoods[$k]['name'] = $goods['name'];
                    $shopGoods[$k]['say'] = $goods['say'];
                    $shopGoods[$k]['picname'] = getRealUrl($goods['picname']);
                    $shopGoods[$k]['price'] = $result['price'];
                    $shopGoods[$k]['spec'] = $result['spec'];
                    $shopGoods[$k]['total'] = $result['price'] * $val['number'];
                    $shopGoods[$k]['rmb'] = number_format($this->rate*$shopGoods[$k]['total'],1); 
                    $shopGoods[$k]['checked'] = false; 
                }

                $shop[$key]['goods'] = $shopGoods;
            }
            returnJson(1,'success',['cart'=>$shop]);
        }
    }

    //获取选中商品价格
    public function getNumber(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            if($this->user['id']>0){
                $map['memberID'] = $this->user['id'];
                $cartNumber = db("Cart")->where($map)->count();
            }else{
                $cartNumber = 0;
            }
            returnJson(1,'success',['cartNumber'=>$cartNumber]);
        }
    }

    //获取选中商品价格
    public function getPrice(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $ids = input("post.ids");
            if ($ids=='') {
                returnJson(0,'缺少参数');
            }
            $ids = explode(",",$ids);
            $map['memberID'] = $this->user['id'];
            $map['id'] = array('in',$ids);
            $list = db('Cart')->where($map)->select();
            $total = 0;
            foreach ($list as $key => $value) {
                $goods = db("Goods")->where('id',$value['goodsID'])->find();
                $result = $this->getGoodsPrice($goods,$value['specID'],$this->flash);
                $total += $result['price'] * $value['number'];
            }
            $rmb = number_format($this->rate*$total,1); 
            returnJson(1,'success',['total'=>$total,'rmb'=>$rmb]);
        }
    }

    //加入购物车
    public function pub(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            
            $goodsID = input('param.goodsID');
            $number = input('param.number');
            $specID = input('param.specID/d',0);
            $action = input('param.action','inc');

            if ($number=='' || !is_numeric($number) || $number<1) {
                returnJson(0,'缺少number');
            }

            if ($goodsID=='' || !is_numeric($goodsID)) {
                returnJson(0,'缺少goodsID');
            }

            if ($specID==='' || !is_numeric($specID)) {
                returnJson(0,'缺少规格参数');
            }

            $goods = db("Goods")->where('id',$goodsID)->find();
            if (!$goods) {
                returnJson(0,'商品不存在');
            }

            if ($specID>0) {
                $spec = db("GoodsSpecPrice")->where(['goods_id'=>$goodsID,'item_id'=>$specID])->find();
                if (!$spec) {
                    returnJson(0,'商品规格错误');
                }
            }

            $empty = getGoodsEmpty($goods);
            if ($empty==1) {
                returnJson(0,'该商品已售罄');
            }

            $db = db("Cart");
            $map['memberID'] = $this->user['id'];
            $map['goodsID'] = $goodsID;
            $map['specID'] = $specID;
            $list = $db->where($map)->find();
            if ($action=='inc') {
                if ($list) {
                    $number = $list['number']+$number;
                    $data['number'] = $number;
                    $data['trueNumber'] = $number*$goods['number'];
                    $db->where($map)->update($data);
                    $cartID = $list['id'];
                }else{
                    $data = [
                        'memberID'=>$this->user['id'],
                        'goodsID'=>$goods['id'],
                        'shopID'=>$goods['shopID'],
                        'specID'=>$specID,
                        'number'=>$number,
                        'trueNumber'=>$number*$goods['number'],
                        'typeID'=>$goods['typeID']
                    ];
                    $cartID = $db->insertGetId($data);
                }
            }else{
                if ($list) {
                    if ($list['number']<=1) {
                        $db->where($map)->delete();
                    }else{
                        $number = $list['number']-$number;
                        $data['number'] = $number;
                        $data['trueNumber'] = $number*$goods['number'];
                        $db->where($map)->update($data);
                    }                
                }
            }
            
            $list = db('Cart')->where(array('memberID'=>$this->user['id']))->select();
            /*$total = 0;
            foreach ($list as $key => $value) {
                $goods = db("Goods")->where('id',$value['goodsID'])->find();
                $result = $this->getGoodsPrice($goods,$value['specID'],$this->flash);
                $total += $result['price'] * $value['number'];
            }
            $rmb = number_format($this->rate*$total,1);*/
            //returnJson(1,'success',['cartID'=>$cartID,'number'=>count($list),'total'=>$total,'rmb'=>$rmb]);
            returnJson(1,'success',['cartID'=>$cartID,'number'=>count($list)]);
        }
    }

    //清空购物车
    public function clear(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $map['memberID'] = $this->user['id'];
            db("Cart")->where($map)->delete();
            returnJson(1,'success');
        }
    }

    //列表页面快速删除并移入收藏夹
    public function toFav(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $ids = input('post.ids');
            if ($ids=='') {
                returnJson(0,'缺少参数');
            }
            $ids = explode(",",$ids);
            $map['id'] = array('in',$ids);
            $map['memberID'] = $this->user['id'];
            $list = db("Cart")->where($map)->select();
            
            foreach ($list as $key => $value) {
                $map['goodsID'] = $value['goodsID'];
                $map['memberID'] = $this->user['id'];
                $res = db('Fav')->where($map)->find();
                if (!$res) {      
                    $data = ['goodsID'=>$value['goodsID'],'memberID'=>$this->user['id']];
                    db('Fav')->insert($data);
                }
                db('Cart')->where('id',$value['id'])->delete();
            }
            returnJson(1,'success');
        }       
    }

    //列表页面快速删除
    public function del(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $ids = input('post.ids');
            if ($ids=='') {
                returnJson(0,'缺少参数');
            }
            $ids = explode(",",$ids);
            $map['id'] = array('in',$ids);
            $map['memberID'] = $this->user['id'];
            db("Cart")->where($map)->delete();

            /*$list = db('Cart')->where(array('memberID'=>$this->user['id']))->select();
            $total = 0;
            foreach ($list as $key => $value) {
                $goods = db("Goods")->where('id',$value['goodsID'])->find();
                $result = $this->getGoodsPrice($goods,$value['specID'],$this->flash);
                $total += $result['price'] * $value['number'];
            }
            $rmb = number_format($this->rate*$total,1);*/
            //returnJson(1,'success',['number'=>count($list),'total'=>$total,'rmb'=>$rmb]);
            returnJson(1,'success');
        }       
    }

    public function package(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $list = db("Cart")->where('memberID',$this->user['id'])->order('typeID asc,trueNumber desc')->select();
            if (!$list) {
                returnJson(0,'购物车中没有商品');
            }
            $result = $this->getYunfeiJson($list);
            returnJson(1,'success',$result);
        } 
    }

    //创建订单
    public function order(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $ids = input('post.ids');
            $couponIds = input('post.couponID');
            if($couponIds){
                $couponIds = explode(",",$couponIds);
            }
            if ($ids=='') {
                returnJson(0,'缺少参数');
            }

            $map['memberID'] = $this->user['id'];
            $address = db('Address')->where($map)->order('def desc , id desc')->find();
            $sender = db('Sender')->where($map)->order('id desc')->find();

            unset($map);
            $map['memberID'] = $this->user['id'];
            $ids = explode(",",$ids);
            $map['id'] = array('in',$ids);
            $shopIds = db('Cart')->where($map)->group('shopID')->column('shopID');
            if (!$shopIds) {
                returnJson(0,'购物车中没有商品');
            }

            $shop = db("Shop")->field('id,name')->whereIn('id',$shopIds)->select();
            $goodsMoney = 0;
            $point = 0;
            $total = 0;
            $payment = 0;
            foreach ($shop as $key => $value) {
                unset($map);
                $map['shopID'] = $value['id'];
                $map['memberID'] = $this->user['id'];
                $shopGoods = db("Cart")->where($map)->select();
                if($couponIds){
                    $couponID = $this->getCouponID($couponIds,$value['id']);
                }     
                $result = $this->getShopOrder($shopGoods,$value,$couponID);
                $shop[$key]['cart'] = $result['cart'];
                $shop[$key]['baoguo'] = $result['baoguo'];
                $shop[$key]['goodsMoney'] = $result['baoguo'];
                $shop[$key]['total'] = $result['total'];
                $shop[$key]['point'] = $result['point'];
                $shop[$key]['coupons'] = $result['coupons'];

                $total += $result['total'];
                $point += $result['point'];
                $goodsMoney += $result['goodsMoney'];
                $payment += $result['baoguo']['totalPrice'];
            }

            $rmb = number_format($this->rate*$total,1);
            returnJson(1,'success',[
                'address'=>$address,
                'sender'=>$sender,
                'point'=>$point,
                'goodsMoney'=>$goodsMoney,
                'total'=>$total,
                'payment'=>$payment,
                'rmb'=>$rmb,
                'shop'=>$shop
            ]);
        }
    }

    public function getShopOrder($cart,$shop,$couponID=null){
        $baoguo = $this->getYunfeiJson($cart);

        $goodsMoney = 0;
        $point = 0;
        foreach ($cart as $key => $value) {
            $goods = db("Goods")->where('id',$value['goodsID'])->find();

            $result = $this->getGoodsPrice($goods,$value['specID'],$this->flash);
            $cart[$key]['name'] = $goods['name'];
            $cart[$key]['say'] = $goods['say'];
            $cart[$key]['picname'] = getRealUrl($goods['picname']);
            $cart[$key]['price'] = $result['price'];
            $cart[$key]['spec'] = $result['spec'];
            $cart[$key]['total'] = $result['price'] * $value['number'];  
            $cart[$key]['rmb'] = number_format($this->rate*$cart[$key]['total'],1); 

            $goodsMoney += $cart[$key]['total'];
            $point += $goods['point'] * $value['trueNumber'];
        }

        //我的优惠券
        unset($map);
        $map['status'] = 0;
        $map['memberID'] = $this->user['id'];
        $map['shopID'] = $shop['id'];
        $coupon = db("CouponLog")->field('id,name,desc,full,dec,goodsID,endTime')->where($map)->select();
        $coupons = []; 
        foreach ($coupon as $key => $value) {
            if($this->checkCoupon($value,$cart,$goodsMoney)){
                if($value['id']==$couponID){
                    $thisCoupon = $value;
                }
                $value['endTime'] = date("Y-m-d H:i:s",$value['endTime']);
                $value['selected'] = false;
                array_push($coupons,$value);
            }
        }

        $total = $goodsMoney + $baoguo['totalPrice'];
        if($thisCoupon){
            $total = $total - $thisCoupon['dec'];
        }
        if($total<=0){
            $total = 1;
        }
        return ['cart'=>$cart,'baoguo'=>$baoguo,'goodsMoney'=>$goodsMoney,'point'=>$point,'total'=>$total,'coupons'=>$coupons];
    }
}