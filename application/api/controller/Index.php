<?php
namespace app\api\controller;

class Index extends Common
{
    public function index(){
    	if (request()->isPost()) {
                        
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $cityID = input('post.cityID');
            $config = tpCache('member');

            $ad = db("Ad")->field('name,picname,url')->where('cid',1)->select();
            foreach ($ad as $key => $value) {
                $value['picname'] = getThumb($value["picname"],1000,480);
            	$ad[$key]['picname'] = getRealUrl($value['picname']);
            }

            $map['fid'] = 0;
            $map['comm'] = 1;
            $cate = db("GoodsCate")->field('id,name,path,picname')->where($map)->order('sort asc')->select();
            foreach ($cate as $key => $value) {
                $value['picname'] = getThumb($value["picname"],200,200);
            	$cate[$key]['picname'] = getRealUrl($value['picname']);
            }

            //精品
            unset($map);
            $map['show'] = 1;
            $map['jingpin'] = 1;
            $map['group'] = array('elt',$this->user['group']);
            if($cityID>0){
                $map['cityID'] = $cityID;
            }
            $jingpin = db("Goods")->field('id,fid,name,picname,say,price,comm')->where($map)->order('sort asc,id desc')->limit(20)->select();
            foreach ($jingpin as $key => $value) {
                $result = $this->getGoodsPrice($value,0,$this->flash);
                $jingpin[$key]['price'] = $result['price'];
                $jingpin[$key]['rmb'] = round($result['price']*$this->rate,1);

                $value['picname'] = getThumb($value["picname"],400,400);
                $jingpin[$key]['picname'] = getRealUrl($value['picname']);                
            }

            //推荐品牌
            unset($map);
            $map['comm'] = 1;
            $brand = db("Brand")->field('id,name,logo')->where($map)->order('sort asc,id desc')->select();
            foreach ($brand as $key => $value) {
                $value['logo'] = getThumb($value["logo"],200,125);
                $brand[$key]['logo'] = getRealUrl($value['logo']);
            }
   
            //今日抢购
            $flashGoods = [];
            foreach ($this->flash as $key => $value){
                if(count($flashGoods)==15){
                    break;
                }
                if($cityID>0){
                    if($value['cityID']==$cityID && $value['group']<=$this->user['group']){
                        array_push($flashGoods,$value);
                    }
                }else{
                    if($value['group']<=$this->user['group']){
                        array_push($flashGoods,$value);
                    }
                }
            }
            $flash = [];
            $q = [];
            $i = 1;
            foreach ($flashGoods as $key => $value) {
                unset($flashGoods[$key]['spec']);
                unset($flashGoods[$key]['pack']);
                unset($map);
                if($cityID>0){
                    $map['cityID'] = $cityID;
                }
                $map['id'] = $value['goodsID'];
                $map['group'] = array('elt',$this->user['group']);
                $goods = db("Goods")->field('id,name,picname,price,say')->where($map)->find();
                $flashGoods[$key]['marketPrice'] = $goods['price'];
                $flashGoods[$key]['name'] = $goods['name'];
                $goods['picname'] = getThumb($goods["picname"],400,400);
                $flashGoods[$key]['picname'] = getRealUrl($goods['picname']);
                $flashGoods[$key]['rmb'] = round($value['price']*$this->rate,1);
                array_push($q,$flashGoods[$key]);
                if ($i%3==0) {
                    array_push($flash,$q);
                    $q = [];
                }
                $i++;
            }
            if (count($q)>0) {
                array_push($flash,$q);
            }

            $flashTime = checkFlashTime($config['flashTime']);

            unset($map);
            $map['fid'] = 0;
            $map['comm1'] = 1;
            $cateGoods = db("GoodsCate")->field('id,name,path')->where($map)->order('sort asc')->select();
            foreach ($cateGoods as $key => $value) {
                unset($map);
                $map['path'] = array('like',$value['path'].'%');
                $map['show'] = 1;
                $map['comm'] = 1;
                $map['group'] = array('elt',$this->user['group']);
                if($cityID>0){
                    $map['cityID'] = $cityID;
                }
                $goods = db('Goods')->field('id,fid,name,picname,say,price,comm')->where($map)->order('sort asc,id desc')->limit(8)->select();
                foreach ($goods as $k => $val) {

                    $result = $this->getGoodsPrice($val,0,$this->flash);
                    $goods[$k]['price'] = $result['price'];
                    $goods[$k]['rmb'] = round($result['price']*$this->rate,1);

                    $val['picname'] = getThumb($val["picname"],400,400);
                    $goods[$k]['picname'] = getRealUrl($val['picname']);   
                }
                $cateGoods[$key]['goods'] = $goods;

                //推荐商铺
                unset($map);      
                if($cityID>0){
                    $map['cityID'] = $cityID;
                }
                $map['comm'] = $value['id'];
                $map['banner'] = array('neq','');
                $map['group'] = array('elt',$this->user['group']);
                $map['status'] = 1;
                $shop = db("Shop")->field('id,banner')->where($map)->limit(5)->select();
                foreach ($shop as $k => $val) {
                    $val['banner'] = getThumb($val["banner"],760,300);
                    $shop[$k]['banner'] = getRealUrl($val['banner']);
                }
                $cateGoods[$key]['shop'] = $shop;
            }
            
            $city = db("City")->cache(true)->field('id,name')->select();
            returnJson(1,'success',[
            	'ad'=>$ad,
                'city'=>$city,
            	'category'=>$cate,
                'jingpin'=>$jingpin,
                'brand'=>$brand,
                'cateGoods'=>$cateGoods,
                'flash'=>$flash,
                'flashTime'=>$flashTime,
            	'rate'=>$this->rate,
            	'hotkey'=>explode(",", $config['hotkey']),
            ]);
        }
    }	

    public function keyword(){
        if (request()->isPost()) {            
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $config = tpCache('member');
            returnJson(1,'success',[
                'hotkey'=>explode(",", $config['hotkey']),
            ]);
        }
    }
}