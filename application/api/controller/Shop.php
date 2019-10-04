<?php
namespace app\api\controller;
use think\Db;

class Shop extends Auth {

    public function lists(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $cityID = input('post.cityID');
            $fid = input('post.fid',0);
            //店铺
            unset($map);
            $ids = db("ShopCate")->where('cateID',$fid)->column("shopID");
            $map['id'] = array('in',$ids);
            if($cityID>0){
                $map['cityID'] = $cityID;
            }
            $map['group'] = array('elt',$this->user['group']);
            $map['status'] = 1;
            $shop = db("Shop")->where($map)->field('id,name,picname,intr,cityID')->select();
            foreach ($shop as $key => $value) {
                $value['picname'] = getThumb($value['picname'],200,200);
                $shop[$key]['picname'] = getRealUrl($value['picname']);
                $shop[$key]['cityName'] = db("City")->where('id',$value['cityID'])->value("name");

                $goods = db("Goods")->field('id,picname')->where('shopID',$value['id'])->order('id desc')->limit(3)->select();
                foreach ($goods as $k => $val) {
                    $val['picname'] = getThumb($val["picname"],400,400);
                    $goods[$k]['picname'] = getRealUrl($val['picname']);
                }
                $shop[$key]['goods'] = $goods;
            }

            returnJson(1,'success',['shop'=>$shop]);
        }
    }

    public function shopWall(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $cityID = input('post.cityID');
            $cid = input('post.cid');

            $map['cate'] = 5;
            $cate = db('OptionItem')->field('id as cid,name')->where($map)->order('sort asc,id asc')->select();

            unset($map);
            if($cid!=''){
                $map['cid'] = $cid;
            }            
            if($cityID>0){
                $map['cityID'] = $cityID;
            }
            $map['group'] = array('elt',$this->user['group']);
            $list = db('Shop')->field('id,name,intr,cityID,banner,picname')->where($map)->order('id desc,py asc')->limit(6)->select();
            foreach ($list as $k => $val) {
                $val['picname'] = getThumb($val["picname"],200,200);
                $list[$k]['picname'] = getRealUrl($val['picname']);

                $val['banner'] = getThumb($val["banner"],760,300);
                $list[$k]['banner'] = getRealUrl($val['banner']);

                $list[$k]['cityName'] = db("City")->where('id',$val['cityID'])->value("name");
            } 
            returnJson(1,'success',['cate'=>$cate,'data'=>$list]);
        }
    }

    public function shopAll(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $cid = input('post.cid');
            $list = db("Shop")->field('py')->group('py')->order('py asc')->select();
            foreach ($list as $key => $value) {
                $map['py'] = $value['py'];
                if($cid!='' && is_numeric($cid)){
                    $map['cid'] = $cid;
                }
                $shop = db("Shop")->field('id,cityID,picname,name,intr')->where($map)->order('id desc')->select();
                foreach ($shop as $k => $val) {
                    $val['picname'] = getThumb($val['picname'],200,200);
                    $shop[$k]['picname'] = getRealUrl($val['picname']);

                    $shop[$k]['cityName'] = db("City")->where('id',$val['cityID'])->value("name");
                }
                $list[$key]['child'] = $shop;
            }

            $category = db("OptionItem")->field('id as cid,name')->where('cate',5)->order('sort asc,id asc')->select();
            returnJson(1,'success',['category'=>$category,'shop'=>$list]);
        }
    }

    public function index(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $shopID = input('post.shopID');
            if ($shopID=='' && !is_numeric($shopID)) {
                returnJson(0,'参数错误');
            }
            $map['id'] = $shopID;
            $map['group'] = array('elt',$this->user['group']);
            $map['status'] = 1;
            $shop = db('Shop')->field('id,name,intr,picname,cate')->where($map)->find();
            if(!$shop){
                returnJson(0,'店铺不存在');
            }

            if($shop['cate']!=''){
                $cateIds = explode(",",$shop['cate']);
                $cate = db("GoodsCate")->field('id,name,path,picname')->whereIn('id',$cateIds)->order('sort asc')->select();
                foreach ($cate as $key => $value) {
                    $value['picname'] = getThumb($value["picname"],200,200);
                    $cate[$key]['picname'] = getRealUrl($value['picname']);
                }
            }

            $shop['picname'] = getThumb($shop['picname'],200,200);
            $shop['picname'] = getRealUrl($shop['picname']);
            $shop['fav'] = db("ShopFav")->where('shopID',$shop['id'])->count();

            unset($map);
            $map['shopID'] = $shopID;
            $map['memberID'] = $this->user['id'];
            $res = db("ShopFav")->where($map)->find();
            if($res){
                $shop['faved'] = 1;
            }else{
                $shop['faved'] = 0;
            }

            //推荐
            unset($map);
            $map['comm'] = 1;
            $map['shopID'] = $shopID;
            $map['show'] = 1;
            $commend = db("Goods")->field('id,name,picname,say,price,comm')->where($map)->order('sort asc,id desc')->limit(20)->select();
            foreach ($commend as $key => $value) {
                $value['picname'] = getThumb($value["picname"],400,400);
                $commend[$key]['picname'] = getRealUrl($value['picname']);
                $commend[$key]['rmb'] = round($value['price']*$this->rate,1);
            }

            //优惠券
            unset($map);
            $map['status'] = 1;
            $map['shopID'] = $shopID;
            $coupon = db('Coupon')->where($map)->order('id desc')->find();
            if($coupon){
                $coupon['number'] = db("CouponLog")->where('couponID',$coupon['id'])->count();

                unset($map);
                $map['couponID'] = $coupon['id'];
                $map['memberID'] = $this->user['id'];
                $coupon['flag'] = db("CouponLog")->where($map)->count();
            }            

            returnJson(1,'success',[
                'shop'=>$shop,
                'cate'=>$cate,
                'commend'=>$commend,    
                'coupon'=>$coupon
            ]);
        }
    }

    public function info(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $shopID = input('post.shopID');
            if ($shopID=='' && !is_numeric($shopID)) {
                returnJson(0,'参数错误');
            }
            $map['id'] = $shopID;
            $map['group'] = array('elt',$this->user['group']);
            $map['status'] = 1;
            $shop = db('Shop')->field('id,name,picname,intr,mp4,image,content,tel,openTime,address,email')->where($map)->find();
            if(!$shop){
                returnJson(0,'店铺不存在');
            }     
            $shop['picname'] = getThumb($shop['picname'],200,200);
            $shop['picname'] = getRealUrl($shop['picname']);
            
            $shop['mp4'] = getRealUrl($shop['mp4']);

            $shop['content'] = str_replace("\n","<br />",$shop['content']);

            if($shop['image']!=''){
                $image = explode(",",$shop['image']);
                foreach ($image as $key => $value) {
                    $image[$key] = getRealUrl($value);
                }
                $shop['image'] = $image;
            }

            $shop['fav'] = db("ShopFav")->where('shopID',$shop['id'])->count();

            unset($map);
            $map['shopID'] = $shopID;
            $map['memberID'] = $this->user['id'];
            $res = db("ShopFav")->where($map)->find();
            if($res){
                $shop['faved'] = 1;
            }else{
                $shop['faved'] = 0;
            }

            //评论
            unset($map);
            $map['status'] = 1;
            $map['shopID'] = $shopID;
            $comment = db("ShopComment")->where($map)->limit(5)->order('id desc')->select();
            foreach ($comment as $key => $value) {
                $comment[$key]['headimg'] = getUserFace($value['headimg']);
                if($value['images']!=''){
                    $images = explode("|", $value['images']);
                    foreach ($images as $k => $val) {
                        $images[$k] = getRealUrl($val);
                    }
                    $comment[$key]['images'] = $images;
                }
            }

            returnJson(1,'success',[
                'shop'=>$shop,
                'comment'=>$comment,
            ]);
        }
    }

    //收藏
    public function doFav(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $shopID = input('post.shopID');
            if ($shopID=='' && !is_numeric($shopID)) {
                returnJson(0,'参数错误');
            }
            $map['shopID'] = $shopID;
            $map['memberID'] = $this->user['id'];
            $res = db('ShopFav')->where($map)->find();
            if ($res) {
                db('ShopFav')->where($map)->delete();
                returnJson(1,'success',['data'=>0]);
             }else{
                $data = ['shopID'=>$shopID,'memberID'=>$this->user['id']];
                $result = db('ShopFav')->insert($data);
                if ($result) {
                    returnJson(1,'success',['data'=>1]);
                }else{
                    returnJson(0,'操作失败');
                }                
            }
        }
    }

    //写评论
    public function doComment(){
        if (request()->isPost()) {
            //if(!checkFormDate()){returnJson(0,'ERROR');}
            $shopID = input('post.shopID');
            $content = input('post.content');
            $images = input('post.images');
            if ($shopID=='' && !is_numeric($shopID)) {
                returnJson(0,'参数错误');
            }
            if ($content=='') {
                returnJson(0,'请输入评论内容');
            }

            if ($images!='') {
                $imgArr = explode("###",$images);
                $images = '';
                $thumb = '';
                foreach ($imgArr as $key => $value) {
                    $path = config('UPLOAD_PATH').'comment/';
                    $fileName = createNonceStr();
                    $fileUrl = $this->base64ToImg($path,$fileName,$value);       
                    if ($key==0) {
                        $images = $fileUrl;     
                    }else{
                        $images .= '|'.$fileUrl; 
                    }
                }
            }

            $data['shopID'] = $shopID;
            $data['content'] = $content;
            $data['images'] = $images;
            $data['memberID'] = $this->user['id'];
            $data['headimg'] = $this->user['headimg'];
            $data['nickname'] = $this->user['nickname'];
            $data['status'] = 1;
            $data['createTime'] = time();
            $res = db("ShopComment")->insert($data);
            if($res){
                returnJson(1,'品论发布成功');
            }else{
                returnJson(0,'操作失败');
            }
        }
    }

    public function comment(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $page = input('post.page/d',1);
            $shopID = input('post.shopID/d',0);
            $pagesize = input('post.pagesize',10);

            $firstRow = $pagesize*($page-1); 
            
            if($shopID>0){
                $map['shopID'] = $shopID;
            }
            $map['status'] = 1;
            $obj = db('ShopComment');
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
}