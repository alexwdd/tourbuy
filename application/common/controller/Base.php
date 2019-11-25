<?php
namespace app\common\controller;
use think\Controller;
use think\Request;
use think\Db;

class Base extends Controller {

    public $rate;

    public function _initialize(){
        $request= Request::instance();

        $module = $request->module();
        $THEME_PATH = '/application/'.$module.'/view/';
        define('RES', $THEME_PATH . 'common');

        $config = tpCache('basic');
        config('site',$config);   

        $this->rate = $this->getRate();     
    }

    //获取用户财产信息
    public function getUserMoney($userid){
        $finance = db('Finance');
        $map['memberID'] = $userid;
        $list = $finance->field('type,money')->where($map)->select(); 
        $buyPoint = 0;
        $tuijianPoint = 0;
        $outPoint = 0;
        $clearPoint = 0;

        foreach ($list as $key => $value) {
            if ($value['type']==1) {
                $buyPoint += $value['money'];
            }
            if ($value['type']==2) {
                $tuijianPoint += $value['money'];
            }
            if ($value['type']==3) {
                $outPoint += $value['money'];
            } 
            if ($value['type']==4) {
                $clearPoint += $value['money'];
            }  
        }

        $point = $buyPoint + $tuijianPoint - $outPoint - $clearJifen;
        if($point<0){
            $point=0;
        }
        
        $config = tpCache('member');
        $money = floor($point/$config['buy']);
        return array(
            'point' => $point,
            'money' => $money
        );
    }

    //商品限时抢购已销售数量
    public function getFlashNumber($goodsID){
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y')); 
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $where['goodsID'] = $goodsID;
        $where['createTime'] = array('between',[$beginToday,$endToday]);
        $number = db('OrderDetail')->where($where)->sum('number');
        return $number;
    }

    //商品是否在限时抢购中
    public function checkInFlash($goodsID,$flash){
        $config = tpCache("member");
        $time = $config['flashTime'];
        $time = explode("-",$time);
        $start = strtotime(date($time[0]));
        $end = strtotime(date($time[1]));
        if (time()>$start && time()<$end) {//是否在抢购时间范围        
            foreach ($flash as $key => $value) {
                if($value['goodsID'] == $goodsID){
                    //判断是否抢光了
                    $sellNumber = $this->getFlashNumber($goodsID);
                    if($sellNumber < $value['number']){
                        $per = ($sellNumber/$value['number'])*100;          
                        if($per>100){
                            $per = 100;
                        }
                        $value['per'] = $per;
                        return $value;
                        break;
                    }
                }
            }
            return false;
        }else{
            return false;
        }
    }

    //返回商品的价格，套餐，规格型号
    public function getGoodsDetail($goods,$flashArr){
        $config = tpCache('member');
        //判断是套餐的ID还是商品的ID
        if($goods['fid']>0){
            $fid = $goods['fid'];
        }else{
            $fid = $goods['id'];
        }

        //获取套餐价格
        $pack = db("Goods")->field('id,name,price')->where('fid',$fid)->select();

        //参数规格
        $spec  = db('GoodsSpecPrice')->where("goods_id", $fid)->column("key,key_name,price,spec_img,item_id");  

        //是否今日抢购
        if($flash = $this->checkInFlash($fid,$flashArr)){
            $goods['per'] = $flash['per'];
            $flash['spec'] = unserialize($flash['spec']);
            $flash['pack'] = unserialize($flash['pack']);
         
            $goods['price'] = $flash['price']; 
            foreach ($flash['pack'] as $key => $value) {
                foreach ($pack as $k => $val) {
                    if($val['id'] == $value['packID']){
                        $pack[$k]['price'] = $value['price'];
                    }
                }
            }

            foreach ($flash['spec'] as $key => $value) {
                foreach ($spec as $k => $val) {
                    if($val['item_id'] == $value['specID']){
                        $spec[$k]['price'] = $value['price'];
                    }
                }
            }
            $goods['isFlash'] = 1;
        }else{
            $goods['isFlash'] = 0;
        }   
        foreach ($spec as $key => $value) {
            $spec[$key]['rmb'] =  round($this->rate*$value['price'],1);
            
            $value['spec_img'] = getThumb($value['spec_img'],200,200);
            $spec[$key]['spec_img'] = getRealUrl($value['spec_img']);
        }
        foreach ($pack as $key => $value) {
            $pack[$key]['rmb'] =  round($this->rate*$value['price'],1);            
        }
        return ['goods'=>$goods,'pack'=>$pack,'spec'=>$spec];
    }

    //获取商品价格
    public function getGoodsPrice($goods,$specID,$flashArr){
        $config = tpCache("member");
        //判断是套餐的ID还是商品的ID
        if($goods['fid']>0){
            $fid = $goods['fid'];
        }else{
            $fid = $goods['id'];
        }
        $flash = $this->checkInFlash($fid,$flashArr);//判断是否在今日抢购中
        $spec = [];
        if($goods['fid']==0){
            if($specID>0){//有规格选项
                $spec = db("GoodsSpecPrice")->field('key_name,price,jiesuan,weight,wuliuWeight,store_count')->where('item_id',$specID)->find(); 
                if($flash){ //今日抢购      
                    $flash['spec'] = unserialize($flash['spec']); 
                    foreach ($flash['spec'] as $k => $val) {
                        if($val['specID'] == $specID){
                            $price = $val['price'];
                            break;
                        }
                    }
                }else{
                    $price = $spec['price'];
                }
                $stock = $spec['store_count'];
                $jiesuan = $spec['jiesuan'];
                $inprice = round(($spec['jiesuan']*(100-$goods['servePrice']))/100,2);
                $ztInprice = round(($spec['jiesuan']*(100-$goods['ztServePrice']))/100,2);
                $weight = $spec['weight'];
                $wuliuWeight = $spec['wuliuWeight'];
            }else{
                if($flash){
                    $price = $flash['price'];
                }else{
                    $price = $goods['price'];
                }
                $jiesuan = $goods['jiesuan'];
                $stock = $goods['stock'];
                $inprice = $goods['inprice'];
                $ztInprice = $goods['ztInprice'];
                $weight = $goods['weight'];
                $wuliuWeight = $goods['wuliuWeight'];
            }
        }else{//是套餐
            if($flash){
                $price = $flash['price'];
            }else{
                $price = $goods['price'];
            }
            $jiesuan = $goods['jiesuan'];
            $stock = $goods['stock'];
            $inprice = $goods['inprice'];
            $ztInprice = $goods['ztInprice'];
            $weight = $goods['weight'];
            $wuliuWeight = $goods['wuliuWeight'];
        } 
        return ['price'=>$price,'jiesuan'=>$jiesuan,'inprice'=>$inprice,'ztInprice'=>$ztInprice,'weight'=>$weight,'wuliuWeight'=>$wuliuWeight,'stock'=>$stock,'spec'=>$spec];
    }

    //获取包裹
    public function getYunfeiJson($cart,$province=null,$quhuoType=0){
        foreach ($cart as $key => $value) {
            /*$goods = db('Goods')->where('id',$value['goodsID'])->find(); 
            if($quhuoType==0){
                $cart[$key]['ziti'] = $goods['ziti'];
            }else{
                $cart[$key]['ziti'] = 1;
            }           
            $cart[$key]['name'] = $goods['name'];
            $cart[$key]['short'] = $goods['short'];            
            $cart[$key]['wuliuWeight'] = $goods['wuliuWeight'];            
            $cart[$key]['weight'] = $goods['weight'];
            $cart[$key]['singleNumber'] = $goods['number'];
            $cart[$key]['baoyou'] = $goods['baoyou'];
            $cart[$key]['price'] = $goods['price'];
            $cart[$key]['inprice'] = $goods['inprice'];
            $cart[$key]['ztInprice'] = $goods['ztInprice'];
            $cart[$key]['specification'] = $goods['specification'];
            $cart[$key]['jiesuan'] = $goods['jiesuan'];
            $cart[$key]['expressID'] = $goods['expressID'];*/ 

            $brand = db("Brand")->where('id',$value['brandID'])->value("name");
            $cart[$key]['brand'] = $brand;
            $cart[$key]['extra'] = ($value['jiesuan']/$value['singleNumber'])*0.215 + 5;//附加费
        }
        $ziti = [];
        $baoguoArr = [];
        foreach ($cart as $key => $value) {
            if ($value['ziti']==1) {
                array_push($ziti, $cart[$key]);
                unset($cart[$key]);
            }
        }              

        $express = $this->getExpressGoods($cart);   
        foreach ($express as $key => $value) {
            $className = "\\pack\\".$value['express']['model'];
            $cart = new $className($value['goods'],$value['express']);
            $baoguoArr = $cart->getBaoguo();
        }
           
        if(count($ziti)>0){
            $zitiBaoguo = [
                'type'=>0,              //类型
                'totalNumber'=>1,       //总数量
                'totalWeight'=>0,       //商品总重量
                'totalWuliuWeight'=>0,  //包装后总重量
                'totalPrice'=>0,        //商品中金额
                'yunfei'=>0,            //运费
                'insideFee'=>0,         //境内运费
                'extend'=>0,
                'express'=>'自提',
                'expressID'=>0,
                'status'=>1,
                'goods'=>$ziti,
            ];            
            $baoguoArr = array_merge($baoguoArr,[$zitiBaoguo]);
        } 

        $totalWeight = 0;
        $totalWuliuWeight = 0;
        $totalPrice = 0;
        $totalExtend = 0;
        $totalInprice = 0;
        $totalInsideFee = 0;
        foreach ($baoguoArr as $key => $value) {
            $totalWeight += $value['totalWeight'];
            $totalWuliuWeight += $value['totalWuliuWeight'];
            $totalPrice += $value['yunfei'];
            $totalExtend += $value['extend'];
            $totalInprice += $value['inprice'];
            $totalInsideFee += $value['insideFee'];
        }

        $data = [
            'totalWeight'=>fix_number_precision($totalWeight,2),
            'totalWuliuWeight'=>fix_number_precision($totalWuliuWeight,2),
            'totalPrice'=>fix_number_precision($totalPrice,2),
            'totalExtend'=>fix_number_precision($totalExtend,2),
            'totalInprice'=>fix_number_precision($totalInprice,2),
            'totalInsideFee'=>fix_number_precision($totalInsideFee,2),
            'baoguo'=>$baoguoArr,
        ];     
        return $data;
    }

    public function getExpressGoods($cart){
        $expressID = [];
        foreach ($cart as $key => $value) {
            if(!in_array($value['expressID'],$expressID)){
                array_push($expressID,$value['expressID']);
            }
        }
        $data = [];
        foreach ($expressID as $key => $value) {
            $express = db("Express")->field('id,name,weight,price,money,model')->where('id',$value)->find();
            $temp = ['express'=>$express,'goods'=>[]];
            foreach ($cart as $k => $val) {
                if($val['expressID']==$value){
                    array_push($temp['goods'],$val);
                }
            }
            array_push($data,$temp);
        }
        return $data;
    }

    //生成随机优惠券码
    public function getCouponNo(){
        $randNum = rand(1000000000, 9999999999);
        $map['code'] = $randNum;
        $count = db("CouponLog")->where($map)->count();
        if ($count>0) {
            return $this->getOrderNo();
        }
        return $randNum;
    }

    //当前汇率
    public function getRate(){
        if (cache("rate")) {
            return cache("rate");
        }else{
            $config = tpCache("supay");
            $data['merchant_id'] = $config['SUPAY_ID'];
            $data['authentication_code'] = $config['SUPAY_KEY'];
            $data['currency'] = 'AUD';
            $data['source'] = 'A';
            $str = 'merchant_id='.$config['SUPAY_ID'].'&authentication_code='.$config['SUPAY_KEY'].'&currency='.$data['currency'].'&source='.$data['source'];
            $token = md5($str);
            $data['token'] = $token;      
            $url = 'https://api.superpayglobal.com/payment/bridge/get_current_rate';
            $result = $this->https_post($url,$data);
            $result = json_decode($result,true);
            if($result['query_success']=='T'){
                cache("rate",$result['rate'],3600);
                return $rate;
            }else{
                return 0;
            }
        }        
    }

    //获取购物车信息
    public function getCartInfo($cart){
        $total = 0;
        foreach ($cart as $key => $value) {
            $goods = db('Goods')->where('id='.$value['goodsID'])->find();
            if($value['specID']>0){
                $spec = db("GoodsSpecPrice")->where('item_id',$value['specID'])->find();
                $goods['price'] = $spec['price'];
            }
            //贴心服务需要计算商品个数，所以要乘套餐里边商品的数量
            $total  += $goods['price'] * $value['number'];
            //$weight += $goods['weight'] * $value['trueNumber'];
        }
        $number = count($cart);
        return array('number'=>$number,'total'=>$total); 
    }

    //检查优惠券是否可用
    /*
    total 订单商品总金额
    */
    public function checkCoupon($coupon,$cart,$total){
        if($total < $coupon['full']){
            return false;
        }
        if($coupon['goodsID']!=''){
            $goodsIds = explode(",", $coupon['goodsID']);
            foreach ($cart as $key => $value) {
                if(in_array($value['goodsID'],$goodsIds)){
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    //注册就送的优惠券
    public function autoCoupon($user){
        $map['register'] = 1;
        //$map['status'] = 1;
        $coupon = db("Coupon")->where($map)->select();
        $data = [];
        foreach ($coupon as $key => $value) {
            for ($i=0; $i < $value['number']; $i++) { 
                if($value['forever']==1){
                    $endTime = time()+3650*86400;
                }else{
                    $endTime = time()+$value['day']*86400;
                }
                $temp = [
                    'shopID'=>$value['shopID'],
                    'memberID'=>$user['id'],
                    'nickname'=>$user['nickname'],
                    'couponID'=>$value['id'],
                    'code'=>$this->getCouponNo(),
                    'name'=>$value['name'],
                    'desc'=>$value['desc'],
                    'full'=>$value['full'],
                    'dec'=>$value['dec'],
                    'intr'=>$value['intr'],
                    'online'=>$value['online'],
                    'forever'=>$value['forever'],
                    'goodsID'=>$value['goodsID'],
                    'createTime'=>time(),
                    'useTime'=>0,
                    'status'=>0,
                    'endTime'=>$endTime
                ];
                array_push($data,$temp);
            }            
        }
        if (count($data)>0) {
            db("CouponLog")->insertAll($data);
        }
    }

    //获取随机订单号
    public function getOrderNo(){
        $order_no = getStoreOrderNo();
        $map['order_no'] = $order_no;
        $count = db("Order")->where($map)->count();
        if ($count>0) {
            return $this->getOrderNo();
        }
        return $order_no;
    }

    public function base64ToImg($path , $name , $data){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $data, $result)){
            $type = $result[2];
            if(!in_array($type,array("jpg","png","bmp","jpeg","gif"))){
                return false;
            }
        
            if(!file_exists($path)){
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir('./'.$path, 0700, true);
            }
            $new_file = $path.$name.".{$type}";  
            if (file_put_contents('.'.$new_file, base64_decode(str_replace($result[1], '', $data)))){
                return $new_file;
            }else{
                return false;
            }
        }
    }

    public function getAlipayUrl($order){ 
        $config = tpCache("supay");
        $data['merchant_id'] = $config['SUPAY_ID'];
        $data['authentication_code'] = $config['SUPAY_KEY'];
        $data['product_title'] = urldecode('途买在线支付');
        $data['merchant_trade_no'] = $order['out_trade_no'];
        $data['currency'] = 'AUD';
        $data['total_amount'] = $order['money'];
        //$data['total_amount'] = 0.01;
        $data['create_time'] = urldecode(date("Y-m-d H:i:s",time()));
        $data['notification_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/www/notify/index.html';
        $data['return_url'] = 'http://m.tourbuy.net/pay/return/'.$order['out_trade_no'];
        $data['mobile_flag'] = 'T';
        $str = 'merchant_id='.$config['SUPAY_ID'].'&authentication_code='.$config['SUPAY_KEY'].'&merchant_trade_no='.$data['merchant_trade_no'].'&total_amount='.$data['total_amount'];
        $token = md5($str);
        $data['token'] = $token;    
        $query = http_build_query($data);
        $url = 'https://api.superpayglobal.com/payment/bridge/merchant_request?'.$query;
        return $url;
    }

    public function getWeixinUrl($order,$shopID=null){
        $config = tpCache("supay");
        $data['merchant_id'] = $config['SUPAY_ID'];
        $data['authentication_code'] = $config['SUPAY_KEY'];
        $data['product_title'] = '途买在线支付';
        $data['merchant_trade_no'] = $order['out_trade_no'];
        $data['currency'] = 'AUD';
        $data['total_amount'] = $order['money'];
        //$data['total_amount'] = 0.01;
        $data['create_time'] = date("Y-m-d H:i:s",time());
        $data['notification_url'] = 'http://'.$_SERVER['HTTP_HOST'].'/www/notify/index.html';
        if($shopID){
            $data['return_url'] = 'http://shop.tourbuy.net/pay/return/'.$order['out_trade_no'].'?shopID='.$shopID;
            $data['redirect_url'] = 'http://shop.tourbuy.net/pay/return/'.$order['out_trade_no'].'?shopID='.$shopID;
        }else{
            $data['return_url'] = 'http://m.tourbuy.net/pay/return/'.$order['out_trade_no'];
            $data['redirect_url'] = 'http://m.tourbuy.net/pay/return/'.$order['out_trade_no'];
        } 
        $data['return_target'] = 'WX';
        $str = 'merchant_id='.$config['SUPAY_ID'].'&authentication_code='.$config['SUPAY_KEY'].'&merchant_trade_no='.$data['merchant_trade_no'].'&total_amount='.$data['total_amount'];

        $token = md5($str);
        $data['token'] = $token;

        $url = 'https://api.superpayglobal.com/payment/wxpayproxy/merchant_request';
        $result = $this->https_post($url,$data);        
        $result = json_decode($result,true);
        return $result;     
    }

    public function https_post($url,$data = null){
        $ch = curl_init();
        $header = "Accept-Charset: utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);      
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);       
        return $output;
    }    

    //创建EWE电子面单
    public function createEweOrder($order){
        $config = tpCache("kuaidi");
        $goods = db("OrderDetail")->where("baoguoID",$order['id'])->select();
        $items = [];
        foreach ($goods as $k => $val) {      
            $temp['ItemName'] = $val['short'];
            $temp['Quantity'] = $val['number'];
            array_push($items,$temp);
        }
        
        $sender = [
            'Name'=>$order['sender'],
            'Phone'=>$order['senderTel'],
        ];
        $receiver = [
            'Name'=>$order['name'],
            'Phone'=>$order['tel'],
            'State'=>$order['province'],
            'City'=>$order['city'],
            'Suburb'=>$order['county'],
            'Street'=>$order['addressDetail'],
        ];
        if(in_array($order['type'],[1,2])){
            $IsEconomic = true;
        }else{
            $IsEconomic = false;
        }
   
        $data = [
            //'USERNAME'=>"dl-syd",   //测试账号
            'USERNAME'=>$config['ewe_username'], //正式账号
            'APIPASSWORD'=>$config['ewe_password'],
            'BoxNo'=>'',
            'TotalPackage'=>1,
            'IsEconomic'=>$IsEconomic,
            'Items'=>$items,
            'Sender'=>$sender,
            'Receiver'=>$receiver
        ];

        //$url = 'https://newomstest.ewe.com.au/eweApi/ewe/api/createOrder';//测试环境
        $url = 'https://jerryapi.ewe.com.au/eweApi/ewe/api/createOrder';  //生产环境
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/javascript'));
        $result = curl_exec($ch);
        $result = json_decode($result,true);
        if ($result['Status']==0) {
            $update = [
                'kdNo'=>$result['Payload']['BOXNO'],
                'printURL'=>$result['Payload']['PrintURL'],
            ];
            db("OrderBaoguo")->where('id',$order['id'])->update($update);
            return ['code'=>1,'msg'=>$result['Message']];
        }else{
            return ['code'=>0,'msg'=>$result['Message']];
        }
    }

    public function uploadEwePerson($order){
        $data = [
            'image1'=>base64EncodeImage('.'.$order['front']),
            'image2'=>base64EncodeImage('.'.$order['back']),
            'name'=>$order['name'],
            'mobile'=>$order['tel'],
            'idCardNo'=>$order['sn'],
        ];
        $url = 'https://api.ewe.com.au/oms/api/Contacts/IdUpload';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/javascript'));
        $result = curl_exec($ch);
        $result = json_decode($result,true);
        if ($result['Status']==0) {
            db("OrderBaoguo")->where($map)->setField('snStatus',1);
        }
    }

    public function createPxOrder($order){
        $config = tpCache("kuaidi");
        $goods = db("OrderDetail")->where("baoguoID",$order['id'])->select();
        $items = [];
        foreach ($goods as $k => $val) {     
            $temp['ItemBrand'] = $val['brand'];
            $temp['Specifications'] = $val['specification'];
            $temp['ItemUnitPrice'] = $val['jiesuan'];
            $temp['ItemName'] = $val['short'];
            $temp['ItemQuantity'] = $val['number'];
            $temp['ItemDeclareType'] = '02020000001';
            array_push($items,$temp);
        }
        $data = [
            'Token'=>$config['px_token'],
            'Data'=>[
                'ShipperOrderNo'=>$order['order_no'].'-'.$order['id'],
                'ServiceTypeCode'=>'RW',
                'ConsignerName'=>$order['sender'],
                'ConsignerMobile'=>$order['senderTel'],
                'ItemDeclareCurrency'=>'CNY',
                'ConsigneeName'=>$order['name'],
                'Province'=>$order['province'],
                'City'=>$order['city'],
                'District'=>$order['county'],
                'ConsigneeStreetDoorNo'=>$order['addressDetail'],
                'ConsigneeMobile'=>$order['tel'],
                'ConsigneeIDNumber'=>$order['sn'],
                'OrderWeight'=>'',
                'ITEMS'=>$items,
            ]
        ]; 
        dump($data);
        //$url = 'http://sandbox.transrush.com.au/agent/createPickupItem';//测试环境
        $url = 'http://www.transrush.com.au/agent/createPickupItem';     //生产环境
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/javascript'));
        $result = curl_exec($ch);
        $result = json_decode($result,true);
        if ($result['ResponseCode']=='10000') {
            $update = [
                'kdNo'=>$result['Data']
            ];
            db("OrderBaoguo")->where('id',$order['id'])->update($update);
            return ['code'=>1,'msg'=>$result['Message']];
        }else{
            return ['code'=>0,'msg'=>$result['Message']];
        }
    }

    public function uploadPxPerson($order){
        $config = tpCache("kuaidi");
        $data = [
            'Token'=>$config['px_token'],
            'Data'=>[
                'ReferenceNumber'=>$order['order_no'].'-'.$order['id'],
                'ReceiverID'=>$order['sn'],
                'name'=>$order['name'],
                'ReceiverIDFacePath'=>base64EncodeImage('.'.$order['front']),
                'ReceiverIDBackPath'=>base64EncodeImage('.'.$order['back']),
            ]
        ];        

        //$url = 'http://sandbox.transrush.com.au/Agent/uploadIdInfo'; //测试环境
        $url = 'http://www.transrush.com.au/Agent/uploadIdInfo'; //生产环境
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/javascript'));
        $result = curl_exec($ch);
        $result = json_decode($result,true);
        /*echo '<meta charset="UTF-8">';
        dump($result);die;*/
        if ($result['Status']==0) {
            db("OrderBaoguo")->where($map)->setField('snStatus',1);
        }
    }

    //返还积分和奖金
    public function saveJiangjin($order){        
        $fina = $this->getUserMoney($order['memberID']);
        if($order['point']>0){
            $jdata = array(
                'type' => 1,
                'money' => $order['point'],
                'memberID' => $order['memberID'],  
                'doID' => $order['memberID'],
                'oldMoney'=>$fina['point'],
                'newMoney'=>$fina['point']+$order['point'],
                'admin' => 2,
                'msg' => '购买商品，获得'.$order['point'].'消费积分',
                'extend1' => $order['id'],
                'createTime' => time()
            ); 
            db("Finance")->insert( $jdata );
        }
        if($order['bonus']>0 && $order['tjID']>0){            
            $fina = $this->getUserMoney($order['tjID']);
            $fdata = array(
                'type' => 2,
                'money' => $order['bonus'],
                'memberID' => $order['tjID'],  
                'doID' => $order['memberID'],
                'oldMoney'=>$fina['point'],
                'newMoney'=>$fina['point']+$order['bonus'],
                'admin' => 2,
                'msg' => '会员'.$order['memberID'].'购买商品，获得'.$order['bonus'].'奖励积分',
                'extend1' => $order['id'],
                'createTime' => time()
            ); 
            db("Finance")->insert( $fdata );
        }
    }
}
