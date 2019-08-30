<?php
namespace app\api\controller;

class Index extends Common
{
    public function index(){
    	if (request()->isPost()) {
                        
            if(!checkFormDate()){returnJson(0,'ERROR');}

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

            $push = db('OptionItem')->field('value,name,ext')->where('cate',1)->select();
            foreach ($push as $key => $value) {
                $push[$key]['goods'] = [];
                $ids = db('GoodsPush')->field('goodsID')->where('cateID',$value['value'])->order('updateTime desc')->limit(6)->select();
                if($ids){                    
                    foreach ($ids as $k => $val) {
                        $goods = db("Goods")->field('id,name,picname,price,say')->where('id',$val['goodsID'])->find();                
                        $goods['picname'] = getThumb($goods["picname"],400,400);
                        $goods['picname'] = getRealUrl($goods['picname']);
                        $goods['rmb'] = round($goods['price']*$this->rate,2);
                        array_push($push[$key]['goods'],$goods);
                     
                    }
                }
            }

            //推荐
            $commend = db("Goods")->field('id,name,picname,say,price,comm')->where('comm',1)->order('sort asc,id desc')->limit(20)->select();
            foreach ($commend as $key => $value) {
                $value['picname'] = getThumb($value["picname"],400,400);
                $commend[$key]['picname'] = getRealUrl($value['picname']);
                $commend[$key]['rmb'] = round($value['price']*$this->rate,2);
            }
   
            //今日抢购
            $flashGoods = [];
            foreach ($this->flash as $key => $value) {
                if($key<15){
                    array_push($flashGoods,$value);
                }
            }
            $flash = [];
            $q = [];
            $i = 1;
            foreach ($flashGoods as $key => $value) {
                unset($flashGoods[$key]['spec']);
                unset($flashGoods[$key]['pack']);
                $goods = db("Goods")->field('id,name,picname,price,say')->where('id',$value['goodsID'])->find();
                $flashGoods[$key]['name'] = $goods['name'];
                $goods['picname'] = getThumb($goods["picname"],400,400);
                $flashGoods[$key]['picname'] = getRealUrl($goods['picname']);
                $flashGoods[$key]['rmb'] = round($value['price']*$this->rate,2);
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
            
            returnJson(1,'success',[
            	'ad'=>$ad,
            	'category'=>$cate,
                'push'=>$push,
                'commend'=>$commend,
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