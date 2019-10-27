<?php
namespace app\api\controller;

class Account extends Auth {

    //会员首页
    public function index(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $shopID = input('post.shopID');

            $user['headimg'] = getUserFace($this->user['headimg']);
            $user['nickname'] = $this->user['nickname'];
            $user['name'] = $this->user['name'];
            $user['mobile'] = $this->user['mobile'];
            $user['wechat'] = $this->user['wechat'];
            $user['sn'] = $this->user['sn'];
            $user['id'] = $this->user['id'];

            unset($map);
            if($shopID>0){
                $map['shopID'] = $shopID;
            }
            $map['hide'] = 0;
            $map['memberID'] = $this->user['id'];
            $map['status'] = 0;
            $order1 = db("Order")->where($map)->count();
            $map['status'] = 1;
            $order2 = db("Order")->where($map)->count();
            $map['status'] = 2;
            $order3 = db("Order")->where($map)->count();
            $map['status'] = 3;
            $order4 = db("Order")->where($map)->count();
            $map['status'] = 99;
            $order5 = db("Order")->where($map)->count();

            $fina = $this->getUserMoney($this->user['id']);

            returnJson(1,'success',[
                'fina'=>$fina,
                'order'=>[
                    'nopay'=>$order1,
                    'peihuo'=>$order2,
                    'fahuo'=>$order3,
                    'pingjia'=>$order4,
                    'close'=>$order5
                ],
                'user'=>$user,
            ]);
        }
    }

    //个人信息
    public function userinfo(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $user['headimg'] = getUserFace($this->user['headimg']);
            $user['nickname'] = $this->user['nickname'];
            $user['name'] = $this->user['name'];
            $user['mobile'] = $this->user['mobile'];
            $user['wechat'] = $this->user['wechat'];
            $user['sn'] = $this->user['sn'];
            $user['id'] = $this->user['id'];
            returnJson(1,'success',['user'=>$user]);
        }
    }

    //收藏
    public function doFav(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $goodsID = input('post.goodsID');
            if ($goodsID=='' && !is_numeric($goodsID)) {
                returnJson(0,'参数错误');
            }
            $map['goodsID'] = $goodsID;
            $map['memberID'] = $this->user['id'];
            $res = db('Fav')->where($map)->find();
            if ($res) {
                returnJson(1,'success');
             }else{
                $data = ['goodsID'=>$goodsID,'memberID'=>$this->user['id']];
                $result = db('Fav')->insert($data);
                if ($result) {
                    returnJson(1,'success');
                }else{
                    returnJson(0,'操作失败');
                }                
            }
        }
    }

    //我的收藏
    public function fav(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
             $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);

            $firstRow = $pagesize*($page-1); 
 
            $map['memberID'] = $this->user['id'];
            $obj = db('Fav');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->field('id,goodsID')->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            foreach ($list as $key => $value) {
                unset($map);
                $map['id'] = $value['goodsID'];
                $goods = db('Goods')->field('id,name,picname,price,say,marketPrice,comm,empty,tehui,flash,baoyou')->where($map)->find();
                if($goods){
                    $goods['picname'] = getRealUrl($goods['picname']);
                    $goods['rmb'] = round($value['price']*$this->rate,1);
                }else{
                    $goods = [];
                }                
                $list[$key]['goods'] = $goods;
                $list[$key]['checked'] = false;
            }
            returnJson(1,'success',['next'=>$next,'data'=>$list]);
        }
    }

    //删除收藏
    public function delFav(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $ids = input('post.ids');
            if ($ids=='') {
                returnJson(0,'缺少参数');
            }
            $ids = explode(",",$ids);
            $map['id'] = array('in',$ids);
            $map['memberID'] = $this->user['id'];
            db("Fav")->where($map)->delete();
            returnJson(1,'success');
        }       
    }

    //我的收藏
    public function finance(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $page = input('post.page/d',1);
            $type = input('post.type/d',0);
            $pagesize = input('post.pagesize',10);

            $firstRow = $pagesize*($page-1); 
            
            if($type>0){
                $map['type'] = $type;
            }
            $map['memberID'] = $this->user['id'];
            $obj = db('Finance');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            foreach ($list as $key => $value) {
                $list[$key]['createTime'] = date("Y-m-d H:i:s",$value['createTime']);
                $list[$key]['typeName'] = getMoneyType($value['type']);
            }
            returnJson(1,'success',['next'=>$next,'data'=>$list,'type'=>config('moneyType')]);
        }
    }

    //我的团队
    public function team(){       
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $page = input('post.page/d',1); 
            $pagesize =10;
            $firstRow = $pagesize*($page-1); 

            $obj = db('Member');
            $map['tjID'] = $this->user['id'];
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }           
            $list = $obj->field('id,headimg,nickname,createTime')->where($map)->limit($firstRow.','.$pagesize)->select();
            foreach ($list as $key => $value) {
                $list[$key]['createTime'] = date("Y-m-d H:i:s",$value['createTime']);
                $list[$key]['headimg'] = getUserFace($value['headimg']);
            }
            returnJson(1,'success',['next'=>$next,'total'=>$count,'data'=>$list,'user'=>['team'=>$this->user['team']]]);
        }
    }

    //每日签到
    public function sign(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $beginDate = strtotime(date("Y-m-1"));
            $beginStr = date("Y-m-d",$beginDate);
            $endDate = strtotime("$beginStr +1 month -1 day");
            $dataArr = array();
            for ($i=$beginDate; $i <=$endDate ; $i+=24*3600) { 
                array_push($dataArr,date("m-d",$i));
            }

            $map['createTime'] = array('between',array($beginDate,$endDate));
            $map['memberID'] = $this->user['id'];
            $sign = db('Sign')->where($map)->column('id,signDate');
            if (in_array(date("Y-m-d"),$sign)) {
                $flag = '1';
            }else{
                $flag = '0';
            }           

            foreach ($sign as $key => $value) {
                $sign[$key]=date("m-d",strtotime($value));
            }

            unset($map);
            $list = db("Sign")->field('point,signDate')->where('memberID',$this->user['id'])->order('id desc')->limit(10)->select();

            returnJson(1,'success',['today'=>$flag,'sign'=>$sign,'data'=>$list]);
        }
    }

    //签到
    public function doSign(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache('member');
            $date = date("Y-m-d");
            $map['memberID'] = $this->user['id'];
            $map['signDate'] = $date;
            $list = db('Sign')->where($map)->find();
            if ($list) {
                returnJson(0,'今天您已签过');
            }else{
                $data['signDate'] = $date;
                $data['point'] = $config['sign'];
                $data['memberID'] = $this->user['id'];
                $data['createTime'] = time();
                $list = db('Sign')->insert($data);
                if ($list) {
                    //添加财务记录
                    $data = array(
                        'type' => 1,
                        'money' => $config['sign'],
                        'memberID' => $this->user['id'],     
                        'doID' =>  $this->user['id'],
                        'admin' => 1,
                        'msg' => date("m-d").'签到，奖励'.$config['sign'].'积分。',
                        'extend1'=>0,
                        'createTime' => time()
                    ); 
                    db('Finance')->insert( $data );
                    returnJson(1,'success',['date'=>date("m-d")]);
                }else{
                    returnJson(0,'操作失败');
                }
            }
        }
    }


    //修改密码
    public function password(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $oldpassword = trim(input('post.oldpassword'));
            $password = trim(input('post.password'));
            //$repassword = trim(input('post.repassword'));
            $id = $this->user['id'];

            if($this->user['password']!=md5($oldpassword)){
                returnJson(0,'原始密码错误！');
            }

            /*if($password!=$repassword){
                returnJson(0,'两次密码不一致！');
            }*/

            $user=db('Member');            
            $data['password']=md5($password);
            $rs = $user->where(array('id'=>$id))->update($data);
            if ($rs) {
                returnJson(1,'success');  
            }else{
                returnJson(0,'操作失败');
            }
        }
    }


    //修改资料
    public function edit(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $nickname = input('post.nickname');
            $name = input('post.name');
            $wechat = input('post.wechat');
            $headimg = input('post.headimg');
            $sn = input('post.sn');
            if($nickname){
                $data['nickname'] = $nickname;
            }
            if($name){
                $data['name'] = $name;
            }
            if($wechat){
                $data['wechat'] = $wechat;
            }
            if($headimg){
                $data['headimg'] = $headimg;
            }
            if($name){
                $data['sn'] = $sn;
            }

            $map['id'] = $this->user['id'];
            db("Member")->where($map)->update($data);
            returnJson(1,'操作成功');
        }
    }
        
    //上传图片
    public function upload(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $image = input('post.image');
            $path = config('UPLOAD_PATH').'face/';
            $fileName = createNonceStr();
            $fileUrl = $this->base64ToImg($path,$fileName,$image);
            $fileUrl = getUserFace($fileUrl);
            returnJson(1,'success',['face'=>$fileUrl]);
        }
    }

    //未领取的优惠券
    public function couponList(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $shopID = input('post.shopID');
            $online = input('post.online');
            $forever = input('post.forever');

            $data= [];
            if($shopID!=''){
                $map['shopID'] = $shopID;
            }
            if($online!=''){
                $map['online'] = $online;
            }
            if($forever!=''){
                $map['forever'] = $forever;
            }
            $map['status'] = 1;
            $map['register'] = 0;
            $list = db("Coupon")->where($map)->order('id desc')->select();
            foreach ($list as $key => $value) {
                unset($map);
                $map['couponID'] = $value['id'];
                $map['memberID'] = $this->user['id'];
                $count = db("CouponLog")->where($map)->count();
                if($count>=$value['number']){
                    unset($value);
                }else{
                    array_push($data,$value);
                }
            }
            returnJson(1,'success',['data'=>$data]);
        }
    }

    //我的优惠券
    public function coupon(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $type = input('param.type');
            $shopID = input('param.shopID');
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            if($shopID!=''){
                $map['shopID'] = $shopID;
            }

            $map['status'] = 0;
            $map['endTime'] = array('gt',time());
            $map['memberID'] = $this->user['id'];
            $number1 = db("CouponLog")->where($map)->count();

            unset($map);
            $map['status'] = 1;
            $map['memberID'] = $this->user['id'];
            $number2 = db("CouponLog")->where($map)->count();

            unset($map);
            $map['status'] = 0;
            $map['endTime'] = array('lt',time());
            $map['memberID'] = $this->user['id'];
            $map['forever'] = 0;
            $number3 = db("CouponLog")->where($map)->count();

            unset($map);
            if($type==1){//未使用
                $map['status'] = 0;
                $map['endTime'] = array('gt',time());
            }elseif($type==2){//已使用
                $map['status'] = 1;
            }elseif($type==3){//已失效
                $map['status'] = 0;
                $map['forever'] = 0;
                $map['endTime'] = array('lt',time());
            }
            $map['memberID'] = $this->user['id'];
            $obj = db('CouponLog');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            foreach ($list as $key => $value) {
                if($value['useTime']>0){
                    $list[$key]['useTime'] = date("Y-m-d H:i:s",$value['useTime']);
                }
                $list[$key]['shopName'] = db("Shop")->where('id',$value['shopID'])->value("name");
                $list[$key]['endTime'] = date("Y-m-d H:i:s",$value['endTime']);
                $list[$key]['createTime'] = date("Y-m-d H:i:s",$value['createTime']);
                if($value['goodsID']!=''){
                    $ids = explode(",",$value['goodsID']);
                    unset($map);
                    $map['id'] = array('in',$ids);
                    $map['show'] = 1;
                    $goods = db("Goods")->field('id as goodsID,name')->where($map)->select();
                    $list[$key]['goods'] = $goods;
                }        
            }
            returnJson(1,'success',['next'=>$next,'data'=>$list,'count'=>['number1'=>$number1,'number2'=>$number2,'number3'=>$number3]]);
        }
    }

    //领取优惠券
    public function doCoupon(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $couponID = input('post.couponID');
            $code = input('post.code');

            if ($couponID!='' || is_numeric($couponID)) {
                $map['status'] = 1;
                $map['id'] = $couponID;
                $list = db('Coupon')->where($map)->find();
                if(!$list){
                    returnJson(0,'优惠券不存在');
                }

                $where['couponID'] = $couponID;
                $where['memberID'] = $this->user['id'];
                $count = db("CouponLog")->where($where)->count();
                if($count>=$list['number']){
                    returnJson(0,$list['name'].'每人最多领取'.$list['number'].'张');
                }
                if($list['forever']==1){
                    $endTime = time()+3650*86400;
                }else{
                    $endTime = time()+$list['day']*86400;
                }
                $data = [
                    'shopID'=>$list['shopID'],
                    'memberID'=>$this->user['id'],
                    'nickname'=>$this->user['nickname'],
                    'couponID'=>$couponID,
                    'code'=>$this->getCouponNo(),
                    'name'=>$list['name'],
                    'desc'=>$list['desc'],
                    'full'=>$list['full'],
                    'dec'=>$list['dec'],
                    'intr'=>$list['intr'],
                    'online'=>$list['online'],
                    'forever'=>$list['forever'],
                    'goodsID'=>$list['goodsID'],
                    'status'=>0,
                    'useTime'=>0,
                    'endTime'=>$endTime,
                    'createTime'=>time(),
                ];
                $res = db("CouponLog")->insert($data);
                if ($res) {
                    returnJson(1,'success',['endTime'=>date("Y-m-d H:i:s",$data['endTime'])]);
                }else{
                    returnJson(0,'领取失败');
                }
            }elseif($code!=''){
                $map['code'] = $code;
                $map['memberID'] = 0;
                $list = db("CouponLog")->where($map)->find();
                if (!$list) {
                    returnJson(0,'无效的优惠券码');
                }

                $coupon = db("Coupon")->where('id',$list['couponID'])->find();
                if (!$coupon) {
                    returnJson(0,'优惠券码已失效');
                }

                $where['couponID'] = $coupon['id'];
                $where['memberID'] = $this->user['id'];
                $count = db("CouponLog")->where($where)->count();
                if($count>=$coupon['number']){
                    returnJson(0,$coupon['name'].'每人最多领取'.$coupon['number'].'张');
                }
                if($coupon['forever']==1){
                    $endTime = time()+3650*86400;
                }else{
                    $endTime = time()+$coupon['day']*86400;
                }
                $data = [
                    'memberID'=>$this->user['id'],
                    'nickname'=>$this->user['nickname'],
                    'endTime'=>$endTime
                ];
                $res = db("CouponLog")->where($map)->update($data);
                if ($res) {
                    returnJson(1,'success',['endTime'=>date("Y-m-d H:i:s",$data['endTime'])]);
                }else{
                    returnJson(0,'领取失败');
                }
            }else{
                returnJson(0,'领取失败');
            }       
        }
    }

    //优惠券信息
    public function couponInfo(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $couponID = input('post.couponID');

            if ($couponID=='' || !is_numeric($couponID)) {
                returnJson(0,'参数错误');
            }
        
            $map['id'] = $couponID;
            $map['online'] = 1;
            $map['memberID'] = $this->user['id'];
            $list = db("CouponLog")->where($map)->find();
            if (!$list) {
                returnJson(0,'优惠券不存在');
            }
            if ($list['useTime']>0) {
                returnJson(0,'优惠券已核销，不能重复使用');
            }
            $shop = db("Shop")->where('id',$list['shopID'])->find();
            $list['shopName'] = $shop['name'];
            $list['logo'] = getRealUrl($shop['picname']);
            returnJson(1,'success',['data'=>$list]);
        }
    }

    //优惠券信息
    public function couponUse(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $couponID = input('post.couponID');

            if ($couponID=='' || !is_numeric($couponID)) {
                returnJson(0,'参数错误');
            }
        
            $map['id'] = $couponID;
            $map['online'] = 1;
            $map['memberID'] = $this->user['id'];
            $list = db("CouponLog")->where($map)->find();
            if (!$list) {
                returnJson(0,'优惠券不存在');
            }
            if ($list['useTime']>0) {
                returnJson(0,'优惠券已核销，不能重复使用');
            }

            db("CouponLog")->where($map)->update([
                    'useTime'=>time(),
                    'status'=>1
                ]);
            returnJson(1,'优惠券成功使用');
        }
    }
}