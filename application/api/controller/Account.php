<?php
namespace app\api\controller;

class Account extends Auth {

    //会员首页
    public function index(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $user['headimg'] = getUserFace($this->user['headimg']);
            $user['nickname'] = $this->user['nickname'];
            $user['name'] = $this->user['name'];
            $user['mobile'] = $this->user['mobile'];
            $user['wechat'] = $this->user['wechat'];
            $user['sn'] = $this->user['sn'];
            $user['id'] = $this->user['id'];

            $map['memberID'] = $this->user['id'];
            $map['status'] = 0;
            $map['endTime'] = array('gt',0);
            $map['hide'] = 0;
            $order1 = db("Order")->where($map)->count();

            unset($map);
            $map['memberID'] = $this->user['id'];
            $map['status'] = 0;
            $map['isCut'] = 1;
            $map['hide'] = 0;
            $map['endTime'] = 0;
            $order2 = db("Order")->where($map)->count();

            unset($map);
            $map['hide'] = 0;
            $map['memberID'] = $this->user['id'];
            $map['status'] = 1;
            $order3 = db("Order")->where($map)->count();
            $map['status'] = 2;
            $order4 = db("Order")->where($map)->count();
            $map['status'] = 3;
            $order5 = db("Order")->where($map)->count();
            $map['status'] = 99;
            $order6 = db("Order")->where($map)->count();

            $fina = $this->getUserMoney($this->user['id']);

            $result = getFundBack($fina['point']);
            $config = tpCache('member');

            $last_mont_first_date = date('Y-m-1',strtotime('last month'));
            $last_mont_end_date = date('Y-m-d',strtotime(date('Y-m-1').'-1 day'));
            unset($map);
            $map['createTime'] = array('between',array(strtotime($last_mont_first_date),strtotime($last_mont_end_date)+86399));
            $map['memberID'] = $this->user['id'];
            $lastMonth = db("Finance")->where($map)->sum("money");

            $result['fanli'] = round($fina['fund']*$result['bar'],2);
            $result['baifenbi'] = ($find['point']/12000)*100;

            //为您推荐 
            $obj = db('GoodsPush');
            $list = $obj->field('goodsID')->where('cateID',3)->limit(10)->order('id desc')->select();
            foreach ($list as $key => $value) {                
                $goods = db("Goods")->field('id,name,picname,price,say,marketPrice,comm,empty,tehui,flash,baoyou')->where('id',$value['goodsID'])->find();   

                unset($list[$key]['goodsID']);
                $goods['picname'] = getThumb($goods["picname"],200,200);
                $goods['picname'] = getRealUrl($goods['picname']);
                $goods['rmb'] = round($goods['price']*$this->rate,2);
                $list[$key] = $goods;
            }


            returnJson(1,'success',[
                'goods'=>$list,
                'fina'=>$fina,
                'jifen'=>$result,
                'lastMonth'=>[
                    'money'=>$lastMonth,
                    'rmb'=>round($lastMonth*$this->rate,2)
                ],
                'config'=>[
                    ['jifen'=>$config['jifen1'],'bar'=>$config['back1']],
                    ['jifen'=>$config['jifen2'],'bar'=>$config['back2']],
                    ['jifen'=>$config['jifen3'],'bar'=>$config['back3']],
                    ['jifen'=>$config['jifen4'],'bar'=>$config['back4']],
                    ['jifen'=>$config['jifen5'],'bar'=>$config['back5']],
                ],
                'order'=>[
                    'nopay'=>$order1,
                    'share'=>$order2,
                    'peihuo'=>$order3,
                    'peing'=>$order4,
                    'fahuo'=>$order5,
                    'close'=>$order6,
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
                    $goods['rmb'] = round($value['price']*$this->rate,2);
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

    //我的优惠券
    public function coupon(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $type = input('param.type');
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

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
            $number3 = db("CouponLog")->where($map)->count();

            unset($map);
            if($type==1){//未使用
                $map['status'] = 0;
                $map['endTime'] = array('gt',time());
            }elseif($type==2){//已使用
                $map['status'] = 1;
            }elseif($type==3){//已失效
                $map['status'] = 0;
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

                $data = [
                    'memberID'=>$this->user['id'],
                    'nickname'=>$this->user['nickname'],
                    'couponID'=>$couponID,
                    'code'=>$this->getCouponNo(),
                    'name'=>$list['name'],
                    'desc'=>$list['desc'],
                    'full'=>$list['full'],
                    'dec'=>$list['dec'],
                    'intr'=>$list['intr'],
                    'goodsID'=>$list['goodsID'],
                    'status'=>0,
                    'useTime'=>0,
                    'endTime'=>time()+86400*$list['day'],
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
                $data = [
                    'memberID'=>$this->user['id'],
                    'nickname'=>$this->user['nickname'],
                    'endTime'=>time()+86400*$coupon['day']
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
}