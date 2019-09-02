<?php
namespace app\api\controller;
use think\Db;

class Shop extends Auth {

    public function index(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $shopID = input('post.shopID');
            if ($shopID=='' && !is_numeric($shopID)) {
                returnJson(0,'参数错误');
            }
            $map['id'] = $shopID;
            $shop = db('Shop')->field('id,name,picname,cate')->where($map)->find();
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
                $commend[$key]['rmb'] = round($value['price']*$this->rate,2);
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
            $shop = db('Shop')->field('id,name,picname,intr,mp4,image,content,address')->where($map)->find();
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