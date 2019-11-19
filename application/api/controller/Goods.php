<?php
namespace app\api\controller;
use think\Db;

class Goods extends Common {

    public function category(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $fid = input('post.fid',0);

            $map['fid'] = $fid;
            $list = db('GoodsCate')->field('id,path,name')->where($map)->order('sort asc,id asc')->select();
            foreach ($list as $key => $value) {
                $child = db('GoodsCate')->field('id,path,name,picname')->where('fid',$value['id'])->order('sort asc,id asc')->select();
                foreach ($child as $k => $val) {
                    $val['picname'] = getThumb($val["picname"],400,400);
                    $child[$k]['picname'] = getRealUrl($val['picname']);
                }
                $list[$key]['child'] = $child;
            }
            $config = tpCache("member");
            returnJson(1,'success',['category'=>$list,'hotkey'=>explode(",", $config['hotkey'])]);
        }
    }

    public function categoryGoods(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $path = input('post.path');

            if($path==''){
                returnJson(0,'参数错误');
            }

            $thisCate = db('GoodsCate')->field('id,name')->where('path',$path)->find();
            if(!$thisCate){
                returnJson(0,'分类不存在');
            }

            $cate = db('GoodsCate')->field('id,name')->where('fid',$thisCate['id'])->order('sort asc,id desc')->select();
            foreach ($cate as $key => $value) {
                $map['cid|cid1'] = $value['id'];
                $map['show'] = 1;
                $map['group'] = array('elt',$this->user['group']);
                $goods = db("Goods")->where($map)->field('id,name,picname,say,price,comm,tehui,flash,baoyou,ziti')->order('sort asc,id desc')->select();
                foreach ($goods as $k => $val) {
                    $val['picname'] = getThumb($val["picname"],400,400);
                    $goods[$k]['picname'] = getRealUrl($val['picname']);
                    $goods[$k]['rmb'] = round($val['price']*$this->rate,1);
                }
                $cate[$key]['goods'] = $goods;
            }
            returnJson(1,'success',['data'=>$cate]);
        }
    }

    public function brand(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $cid = input('post.cid');
            $keyword = input('param.keyword');
            $comm = input('param.comm');
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            $cate = '全部';
            if($comm!=''){
                $map['comm'] = $comm;
            }
            if($cid!=''){
                $map['cid'] = $cid; 
                $cate = db("OptionItem")->where('id',$cid)->value("name");
            }
            if($keyword!=''){
                $map['name'] = array('like','%'.$keyword.'%');
                $cate = '品牌搜索';
            }
            $obj = db('Brand');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->field('id,name,logo,py')->where($map)->limit($firstRow.','.$pagesize)->order('py asc')->select();
            foreach ($list as $key => $value) {
                $value['logo'] = getThumb($value['logo'],200,125);
                $list[$key]['logo'] = getRealUrl($value['logo']);
            }
            returnJson(1,'success',['next'=>$next,'cate'=>$cate,'data'=>$list]);
        }
    }

    public function brandAll(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $cid = input('post.cid');
            $list = db("Brand")->field('py')->group('py')->order('py asc')->select();
            foreach ($list as $key => $value) {
                $map['py'] = $value['py'];
                if($cid!='' && is_numeric($cid)){
                    $map['cid'] = $cid;
                }
                $brand = db("Brand")->field('id,logo,name')->where($map)->order('sort asc , id asc')->select();
                foreach ($brand as $k => $val) {
                    $val['logo'] = getThumb($val['logo'],200,125);
                    $brand[$k]['logo'] = getRealUrl($val['logo']);
                }
                $list[$key]['child'] = $brand;
            }

            $category = db("OptionItem")->field('id as cid,name')->where('cate',4)->order('sort asc,id asc')->select();
            returnJson(1,'success',['category'=>$category,'brand'=>$list]);
        }
    }

    public function brandWall(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $map['cate'] = 4;
            $list = db('OptionItem')->field('id as cid,name')->where($map)->order('sort asc,id asc')->select();
            foreach ($list as $key => $value) {
                $child = db('Brand')->field('id,name,logo')->where('cid',$value['cid'])->order('sort asc,py asc')->limit(5)->select();
                foreach ($child as $k => $val) {
                    $val['logo'] = getThumb($val["logo"],200,125);
                    $child[$k]['logo'] = getRealUrl($val['logo']);
                }
                $list[$key]['child'] = $child;
            }
            returnJson(1,'success',['brand'=>$list]);
        }
    }    

    public function lists(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $cityID = input('post.cityID');
            $shopID = input('post.shopID');
            $path = input('post.path');
            $cid = input('post.cid');
            $brandID = input('post.brandID');
            $keyword = input('param.keyword');
            $comm = input('param.comm');
            $order = input('param.order','id');
            $desc = input('param.desc','desc');
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            if($cityID>0){
                $map['cityID'] = $cityID;
            }
            if($shopID>0){
                $map['shopID'] = $shopID;
            }
            if($comm!=''){
                $map['comm'] = $comm;
            }
            if($brandID!=''){
                $map['brandID'] = $brandID;
                $brand = db("Brand")->where('id',$brandID)->find();
            }
            if($cid!=''){
                $map['cid|cid1'] = $cid;
                $cate = db("GoodsCate")->field('id,path,name')->where('id',$cid)->find();
            }
            if($path!=''){
                $map['path|path1'] = array('like',$path.'%');
                $cate = db("GoodsCate")->field('id,path,name')->where('path',$path)->find();
            }
            if($cate){
                $child = db("GoodsCate")->field('id,path,name')->where('fid',$cate['id'])->select();
            }
            if($keyword!=''){
                $map['name|short|keyword'] = array('like','%'.$keyword.'%');
            }
            $map['show'] = 1;
            $map['group'] = array('elt',$this->user['group']);
            $obj = db('Goods');
            $count = $obj->where($map)->count();            
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }

            $list = $obj->field('id,fid,name,picname,price,say,comm,tehui,flash,baoyou,ziti')->where($map)->limit($firstRow.','.$pagesize)->order($order.' '.$desc)->select();

            foreach ($list as $key => $value) {
                $list[$key]['picname'] = getRealUrl($value['picname']);

                $result = $this->getGoodsPrice($value,0,$this->flash);     
                $list[$key]['price'] = $result['price'];
                $list[$key]['rmb'] = round($result['price']*$this->rate,1);
            }

            $city = db("City")->cache(true)->field('id,name')->select();
            returnJson(1,'success',['city'=>$city,'cate'=>$cate,'brand'=>$brand,'child'=>$child,'next'=>$next,'data'=>$list]);
        }
    }

    public function cateGoods(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $config = tpCache('member');
    
            $fid = input('post.fid');
            $shopID = input('post.shopID');
            $path = input('post.path');
            $page = input('post.page/d',1);
            $cityID = input('post.cityID');
            $field = input('post.field');
            $order = input('post.order');
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            $thisCate = db("GoodsCate")->where('id',$fid)->find();

            if($path!=''){
                $map['path'] = array('like',$path.'%');
            }else{
                $map['path'] = array('like',$thisCate['path'].'%');
            }

            if($cityID>0){
                $map['cityID'] = $cityID;
            }
            if($shopID>0){
                $map['shopID'] = $shopID;
            }
            $map['group'] = array('elt',$this->user['group']);
            $map['show'] = 1;
            $obj = db('Goods');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->field('id,fid,name,picname,price,say,comm,tehui,flash,baoyou,ziti')->where($map)->limit($firstRow.','.$pagesize)->order($field.' '.$order)->select();

            foreach ($list as $key => $value) {
                $value['banner'] = getThumb($value["picname"],760,300);
                $list[$key]['picname'] = getRealUrl($value['picname']);
                
                $result = $this->getGoodsPrice($value,0,$this->flash);     
                $list[$key]['price'] = $result['price'];
                $list[$key]['rmb'] = round($result['price']*$this->rate,1);
            }
            
            $cate = db("GoodsCate")->field('id,name,path')->where('fid',$thisCate['id'])->order('sort asc,id asc')->select();

            /*$city = db("City")->cache(true)->field('id,name')->select();*/

            returnJson(1,'success',['next'=>$next,'data'=>$list,'cateName'=>$thisCate['name'],'cate'=>$cate]);
        }
    }

    public function jingpin(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $config = tpCache('member');
    
            $path = input('post.path');
            $page = input('post.page/d',1);
            $cityID = input('post.cityID');
            $field = input('post.field');
            $order = input('post.order');
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            if($path!=''){
                $map['path'] = array('like',$path.'%');
            }

            if($cityID>0){
                $map['cityID'] = $cityID;
            }

            $map['jingpin'] = 1;
            $map['group'] = array('elt',$this->user['group']);
            $map['show'] = 1;
            $obj = db('Goods');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->field('id,fid,name,picname,price,say,comm,tehui,flash,baoyou,ziti')->where($map)->limit($firstRow.','.$pagesize)->order($field.' '.$order)->select();

            foreach ($list as $key => $value) {
                $value['banner'] = getThumb($value["picname"],760,300);
                $list[$key]['picname'] = getRealUrl($value['picname']);

                $result = $this->getGoodsPrice($value,0,$this->flash);
                $list[$key]['price'] = $result['price'];
                $list[$key]['rmb'] = round($result['price']*$this->rate,1);
            }
            
            $cate = db("GoodsCate")->field('id,name,path')->where('fid',0)->order('sort asc,id asc')->select();

            //$city = db("City")->cache(true)->field('id,name')->select();
            returnJson(1,'success',['next'=>$next,'data'=>$list,'cate'=>$cate]);
        }
    }

    public function flash(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $config = tpCache('member');

            $cityID = input('post.cityID');
            $shopID = input('post.shopID');
            $type = input('post.type',1);
            $cid = input('post.cid');
            $keyword = input('param.keyword');
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            if($keyword!=''){
                $map['goodsName'] = array('like','%'.$keyword.'%');
            }
            if(!in_array($type,[1,2])){
                returnJson(0,'type参数错误');
            }
            if($cid!=''){
                $map['cid'] = $cid;
            }

            $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y')); 
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            if ($type==2) {
                $beginToday = $beginToday+86400;
                $endToday = $endToday+86400;
            }

            $map['startDate'] = array('elt',$beginToday);
            $map['endDate'] = array('egt',$endToday);

            if($cityID>0){
                $map['cityID'] = $cityID;
            }
            if($shopID>0){
                $map['shopID'] = $shopID;
            }
            $map['group'] = array('elt',$this->user['group']);
            $obj = db('Flash');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->field('goodsID,goodsName,price,spec,pack,number')->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            if($list){
                $cateID = $obj->where($map)->group('cid')->column("cid");
                $where['id'] = array('in',$cateID);
                $cate = db('GoodsCate')->field('id,path,name')->where($where)->select();
            }
            
            foreach ($list as $key => $value) {                
                $goods = db("Goods")->field('id,name,picname,price,say,comm,tehui,flash,baoyou,ziti')->where('id',$value['goodsID'])->find();             
                $sellNumber = $this->getFlashNumber($value['goodsID']);

                $list[$key]['per'] = floor(($sellNumber/$value['number'])*100);
                $list[$key]['picname'] = getRealUrl($goods['picname']);
                $list[$key]['marketPrice'] = $goods['price'];
                $list[$key]['say'] = $goods['say'];
                $list[$key]['comm'] = $goods['comm'];
                $list[$key]['tehui'] = $goods['tehui'];
                $list[$key]['flash'] = $goods['flash'];
                $list[$key]['baoyou'] = $goods['baoyou'];
                $list[$key]['rmb'] = round($value['price']*$this->rate,1);

                unset($list[$key]['spec']);
                unset($list[$key]['pack']);
                unset($list[$key]['number']);
            }

            $flashTime = checkFlashTime($config['flashTime']);
            returnJson(1,'success',['next'=>$next,'data'=>$list,'flashTime'=>$flashTime,'cate'=>$cate]);
        }
    }

    public function info(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $goodsID = input('post.goodsID');
            if ($goodsID=='' || !is_numeric($goodsID)) {
                returnJson(0,'参数错误');
            }
            $map['id'] = $goodsID;
            $map['show'] = 1;
            $map['group'] = array('elt',$this->user['group']);
            $list = db('Goods')->field('id,fid,name,picname,price,comm,tehui,flash,baoyou,ziti')->where($map)->find();
            if (!$list) {
                returnJson('-1','不存在的商品');
            }
            $list['marketPrice'] = $list['price'];
            $list['picname'] = getThumb($list["picname"],200,200);
            $list['picname'] = getRealUrl($list['picname']);
            //参数规格
            if($list['fid']>0){
                $fid = $list['fid'];
                $filter_spec = [];
            }else{
                $fid = $list['id'];     
                $filter_spec = $this->get_spec($fid);
            } 
            
            $result = $this->getGoodsDetail($list,$this->flash);

            $list = $result['goods'];
            if($list['fid']>0){
                $spec = [];
            }else{
                $spec = $result['spec'];   
            }            
            $pack = $result['pack'];

            $list['rmb'] = round($this->rate*$list['price'],1);  
            returnJson(1,'success',['goods'=>$list,'pack'=>$pack,'spec'=>$spec,'filter_spec'=>$filter_spec]);
        }
    }

    public function detail(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $goodsID = input('post.goodsID');
            if ($goodsID=='' || !is_numeric($goodsID)) {
                returnJson(0,'参数错误');
            }
            $map['id'] = $goodsID;
            $map['show'] = 1;
            $map['group'] = array('elt',$this->user['group']);
            $list = db('Goods')->field('id,shopID,fid,name,picname,image,price,point,content,say,intr,comm,tehui,flash,baoyou,ziti')->where($map)->find();
            if (!$list) {
                returnJson('-1','不存在的商品');
            }
            $list['marketPrice'] = $list['price'];
            
            if ($list['image']=='') {
                $list['image'] = array($list['picname']);            
            }else{
                $list['image'] = explode(",", $list['image']);
            }
            foreach ($list['image'] as $key => $value) {
                $value = getThumb($value,1000,1000);
                $list['image'][$key] = getRealUrl($value);
            }                  
       
            //参数规格
            if($list['fid']>0){
                $fid = $list['fid'];
                $filter_spec = [];
            }else{
                $fid = $list['id'];
                $filter_spec = $this->get_spec($fid);
            }            
            
            $result = $this->getGoodsDetail($list,$this->flash);
            $config = tpCache("member");
            $flashTime = checkFlashTime($config['flashTime']);
            $list = $result['goods'];
            if($list['fid']>0){
                $spec = [];
            }else{
                $spec = $result['spec'];   
            } 
            $pack = $result['pack'];

            $list['rmb'] = number_format($this->rate*$list['price'],1);  

            $goods_txt = strip_tags($list['content']);
            $goods_txt = preg_replace ('/\n/is', '', $goods_txt);
            $goods_txt = preg_replace ('/ |　/is', '', $goods_txt);
            $goods_txt = preg_replace ('/&nbsp;/is', '', $goods_txt);   
            $list['goods_txt'] = $goods_txt;

            preg_match_all("/src=\"?(\/.*?)\"/", $list['content'], $match);
            if ($match[1]){
                $goods_img = $match[1];
                foreach ($goods_img as $key => $value) {
                    $goods_img[$key] = getRealUrl($value);
                }
            }else{
                $goods_img = [];
            }
            $list['goods_img'] = $goods_img;
            
            $list['content'] = htmlspecialchars_decode($list['content']);

            unset($map);
            if($this->user['id']>0){
                $map['memberID'] = $this->user['id'];
                $cartNumber = db("Cart")->where($map)->count();
                $map['goodsID'] = $list['id'];
                $fav = db("Fav")->where($map)->count();
            }else{
                $cartNumber = 0;
                $fav = 0;
            }

            //商品相关优惠券
            unset($map);
            $ids = db("CouponGoods")->where('goodsID',$list['id'])->column('couponID');
            if($ids){
                $ids = implode(",",$ids);
                $coupon = Db::query("select * from pm_coupon where status=1 and shopID=".$list['shopID']." and (goodsID='' or id in (".$ids."))");
            }else{
                $coupon = Db::query("select * from pm_coupon where status=1 and shopID=".$list['shopID']." and goodsID=''");
            }
            foreach ($coupon as $key => $value) {
                $where['couponID'] = $value['id'];
                $where['memberID'] = $this->user['id'];
                $my = db("CouponLog")->where($where)->find();
                if($my){
                    $coupon[$key]['endTime'] = date("Y-m-d H:i:s",$my['endTime']);
                }else{
                    $coupon[$key]['endTime'] = '';
                }
            }

            foreach ($pack as $key => $value) {
                $pack[$key]['checked'] = false;
            }

            //是否明日抢购
            $time = explode("-",$config['flashTime']);

            $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y')); 
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

            $start = strtotime(date($time[0]));
            if (time()>$start) {
                $beginToday = $beginToday+86400;
                $endToday = $endToday+86400;
            }
            
            unset($map);
            $map['startDate'] = array('elt',$beginToday);
            $map['endDate'] = array('egt',$endToday);
            $map['goodsID'] = $fid;
            $tomorrowPrice = db("Flash")->where($map)->value('price');
            if($tomorrowPrice){
                $tomorrow['price'] = $tomorrowPrice;
                $tomorrow['date'] = date("m月d日",$endToday);                
                $tomorrow['time'] = $time[0];
            }else{
                $tomorrow = [];
            }

            //店铺信息
            $shop = db("Shop")->field('id,name,intr,picname')->where('id',$list['shopID'])->find();
            $shop['picname'] = getThumb($shop['picname'],200,200);
            $shop['picname'] = getRealUrl($shop['picname']);
            $shop['faved'] = db("ShopFav")->where(['shopID'=>$shop['id'],'memberID'=>$this->user['id']])->count();

            unset($map);
            $map['shopID'] = $shop['id'];
            $map['id'] = array('neq',$goodsID);
            $about = db("Goods")->field('id,name,picname,price,say')->where('shopID',$shop['id'])->limit(3)->order('comm desc,id desc')->select();
            foreach ($about as $key => $value) {
                $value["picname"] = getThumb($value["picname"],400,400);
                $about[$key]['picname'] = getRealUrl($value['picname']);
                $about[$key]['rmb'] = round($value['price']*$this->rate,1);
            }
            $shop['goods'] = $about;
            $shop['fav'] = db("ShopFav")->where('shopID',$shop['id'])->count();

            //相关评论
            unset($map);
            $map['goodsID'] = $list['id'];
            $map['status'] = 1;
            $comment = db("GoodsComment")->where($map)->limit(3)->order('id desc')->select();
            foreach ($comment as $key => $value) {
                $comment[$key]['headimg'] = getUserFace($value['headimg']);
                $comment[$key]['createTime'] = date("Y-m-d H:i:s",$value['createTime']);
                if($value['images']!=''){
                    $images = explode("|", $value['images']);
                    foreach ($images as $k => $val) {
                        $images[$k] = getRealUrl($val);
                    }
                    $comment[$key]['images'] = $images;                    
                }
            }
            $list['comment'] = $comment;
            returnJson(1,'success',['goods'=>$list,'flashTime'=>$flashTime,'tomorrow'=>$tomorrow,'shop'=>$shop,'cartNumber'=>$cartNumber,'fav'=>$fav,'coupon'=>$coupon,'pack'=>$pack,'spec'=>$spec,'filter_spec'=>$filter_spec]);
        }
    }

    public function comment(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $page = input('post.page/d',1);
            $goodsID = input('post.goodsID/d',0);
            $pagesize = input('post.pagesize',10);

            $firstRow = $pagesize*($page-1); 
            
            if($goodsID==0){
                returnJson(0,'参数错误');
            }
            $map['goodsID'] = $goodsID;
            $map['status'] = 1;
            $obj = db('GoodsComment');
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
                $list[$key]['headimg'] = getUserFace($value['headimg']);
                if($value['images']!=''){
                    $images = explode("|", $value['images']);
                    foreach ($images as $k => $val) {
                        $images[$k] = getRealUrl($val);
                    }
                    $list[$key]['images'] = $images;
                }        
            }
            returnJson(1,'success',['next'=>$next,'data'=>$list]);
        }
    }

    //生成海报
    public function poster(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $goodsID = input('post.goodsID');            
            if ($goodsID=='' || !is_numeric($goodsID)) {
                returnJson(0,'参数错误');
            }
            $map['id'] = $goodsID;
            $map['show'] = 1;
            $map['group'] = array('elt',$this->user['group']);
            $list = db('Goods')->field('id,shopID,fid,name,picname,image,price,point,content,say,intr')->where($map)->find();
            if (!$list) {
                returnJson(0,'不存在的商品');
            }

            $list['rmb'] = number_format($this->rate*$list['price'],1); 
            

            $url = 'http://m.tourbuy.net/goodsDetail?id='.$list['id'].'&shareUser='.$this->user['id'];

            require_once EXTEND_PATH.'qrcode/qrcode.php';
            $value = input("param.url");//二维码数据
            $errorCorrectionLevel = 'Q';//纠错级别：L、M、Q、H
            $matrixPointSize = 5;//二维码点的大小：1到10
            $object = new \QRcode();
            $qrcode = ".".config("UPLOAD_PATH")."qrcode.png";
            $object->png($url, $qrcode , $errorCorrectionLevel, $matrixPointSize, 2);//不带Logo二维码的文件名

            $face = $this->saveFace($this->user['headimg']);

            $list['name'] = $this->break_string($list['name'],20);
            
            $list['picname'] = getThumb($list["picname"],700,700);
            $image = \think\Image::open('./poster.jpg');
            $image->water('.'.$list['picname'],[50,50])            
                  ->text("AU$",'simhei.ttf',18,'#000',[50,780])
                  ->text($list['price'],'simhei.ttf',32,'#000',[90,770])
                  ->text('约 ￥'.$list['rmb'],'simhei.ttf',20,'#666666',[240,775])
                  ->text($list['name'],'simhei.ttf',26,'#666666',[50,830])
                  ->water($face,[50,1100])
                  ->water($qrcode,[570,1060])
                  ->text($this->user['nickname'],'simhei.ttf',36,'#666666',[200,1120])
                  ->text('为您推荐','simhei.ttf',24,'#666666',[200,1170])->save(".".config("UPLOAD_PATH")."poster.png");
            returnJson(1,'success',['url'=>config('site.domain').config("UPLOAD_PATH")."poster.png"]);
        }
    }

    public function saveFace($face){
        $first = substr($face,0,4);
        if ($first != 'http') {
            return ".".$face;
        }

        $header = array('User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:45.0) Gecko/20100101 Firefox/45.0','Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3','Accept-Encoding: gzip, deflate',);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $face);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        $data = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($code == 200) {//把URL格式的图片转成base64_encode格式的！    
            $imgBase64Code = "data:image/jpeg;base64," . base64_encode($data);
        }

        $img_content=$imgBase64Code;//图片内容
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)){ 
            $type = $result[2];//得到图片类型png?jpg?gif? 
            $new_file = ".".config("UPLOAD_PATH")."face.{$type}"; 
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img_content)))){
                return $new_file;
            }
        }
    }

    public function break_string($str,$num){
        preg_match_all("/./u", $str, $arr);//将所有字符转成单个数组
        
        //print_r($arr);
        
        $strstr = '';
        $width = 0;
        $arr = $arr[0];
        foreach($arr as $key=>$string){
            $strlen = strlen($string);//计算当前字符的长度，一个字母的长度为1，一个汉字的长度为3
            //echo $strlen;
            
            if($strlen == 3){
                
                $width += 1;
                
            }else{
                
                $width += 0.5;
                
            }
            
            $strstr .= $string;
            
            //计算当前字符的下一个
            if(array_key_exists($key+1, $arr)){
                $_strlen = strlen($arr[$key+1]);
                 if($_strlen == 3){
                    $_width = 1;
                }else{
                    $_width = 0.5;
                }
                if($width+$_width > $num){
                    $width = 0;
                    $strstr .= "\n";
                }
            }
     
        }
        return $strstr;
    }

    public function get_spec($goods_id){
        //商品规格 价钱 库存表 找出 所有 规格项id
        $keys = db('GoodsSpecPrice')->where("goods_id", $goods_id)->column("GROUP_CONCAT(`key` ORDER BY store_count desc SEPARATOR '_') ");
        $filter_spec = array();        
        if ($keys[0]) {
            $keys = str_replace('_', ',', $keys[0]);
            $sql = "SELECT a.name,a.sort,b.* FROM pm_model_spec AS a INNER JOIN pm_model_spec_item AS b ON a.id = b.specID WHERE b.id IN($keys) ORDER BY b.id";

            $filter_spec2 = \think\Db::query($sql);
            foreach ($filter_spec2 as $key => $val) {
                $filter_spec[$val['name']][] = array(
                    'item_id' => $val['id'],
                    'item' => $val['item'],
                    'checked' => false
                );
            }
        }
        return $filter_spec;
    }
}