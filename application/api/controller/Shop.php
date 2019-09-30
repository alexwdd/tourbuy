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
            $coupon = Db::query("select * from pm_coupon where status=1 and (shopID=0 or shopID=".$shopID.") order by id desc");

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

            returnJson(1,'success',[
                'shop'=>$shop,
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
}