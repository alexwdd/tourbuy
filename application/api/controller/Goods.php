<?php
namespace app\api\controller;

class Goods extends Common {

    public function category(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $map['fid'] = 0;
            $list = db('GoodsCate')->field('id,path,name')->where($map)->order('sort asc,id desc')->select();
            foreach ($list as $key => $value) {
                $child = db('GoodsCate')->field('id,path,name,picname')->where('fid',$value['id'])->order('sort asc,id desc')->select();
                foreach ($child as $k => $val) {
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
                $goods = db("Goods")->where($map)->field('id,name,picname,say,price,marketPrice,comm')->order('sort asc,id desc')->select();
                foreach ($goods as $k => $val) {
                    $val['picname'] = getThumb($val["picname"],400,400);
                    $goods[$k]['picname'] = getRealUrl($val['picname']);
                    $goods[$k]['rmb'] = round($val['price']*$this->rate,2);
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

            $ad = db("Ad")->field('name,picname,url')->where('cid',2)->select();
            foreach ($ad as $key => $value) {
                $value['picname'] = getThumb($value["picname"],600,180);
                $ad[$key]['picname'] = getRealUrl($value['picname']);
            }
            returnJson(1,'success',['brand'=>$list,'ad'=>$ad]);
        }
    }

    public function lists(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $path = input('post.path');
            $cid = input('post.cid');
            $brandID = input('post.brandID');
            $keyword = input('param.keyword');
            $comm = input('param.comm');
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

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
            $obj = db('Goods');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }

            $list = $obj->field('id,name,picname,price,say,marketPrice,comm,tehui,flash,baoyou')->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            foreach ($list as $key => $value) {
                $list[$key]['picname'] = getRealUrl($value['picname']);
                $list[$key]['rmb'] = round($value['price']*$this->rate,2);
            }
            returnJson(1,'success',['cate'=>$cate,'brand'=>$brand,'child'=>$child,'next'=>$next,'data'=>$list]);
        }
    }

    public function push(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $config = tpCache('member');

            $type = input('post.type',1);
            $cid = input('post.cid');
            $keyword = input('param.keyword');
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            if($keyword!=''){
                $map['goodsName'] = array('like','%'.$keyword.'%');
            }
            if(!in_array($type,[1,2,3])){
                returnJson(0,'type参数错误');
            }
            $map['cateID'] = $type;

            if($cid!=''){
                $map['cid'] = $cid;
            }

            $obj = db('GoodsPush');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->field('goodsID')->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            if($list){
                $cateID = $obj->where($map)->group('cid')->column("cid");
                $where['id'] = array('in',$cateID);
                $cate = db('GoodsCate')->field('id,path,name')->where($where)->select();
            }
            
            foreach ($list as $key => $value) {                
                $goods = db("Goods")->field('id,name,picname,price,say,marketPrice,comm,tehui,flash,baoyou')->where('id',$value['goodsID'])->find();   

                unset($list[$key]['goodsID']);
                $goods['picname'] = getRealUrl($goods['picname']);
                $goods['rmb'] = round($goods['price']*$this->rate,2);
                $list[$key] = $goods;
            }
            returnJson(1,'success',['next'=>$next,'data'=>$list,'cate'=>$cate]);
        }
    }

    public function flash(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $config = tpCache('member');

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
                $goods = db("Goods")->field('id,name,picname,price,say,marketPrice,comm,tehui,flash,baoyou')->where('id',$value['goodsID'])->find();             
                $sellNumber = $this->getFlashNumber($value['goodsID']);

                $list[$key]['per'] = floor(($sellNumber/$value['number'])*100);
                $list[$key]['picname'] = getRealUrl($goods['picname']);
                $list[$key]['marketPrice'] = $goods['marketPrice'];
                $list[$key]['say'] = $goods['say'];
                $list[$key]['comm'] = $goods['comm'];
                $list[$key]['tehui'] = $goods['tehui'];
                $list[$key]['flash'] = $goods['flash'];
                $list[$key]['baoyou'] = $goods['baoyou'];
                $list[$key]['rmb'] = round($value['price']*$this->rate,2);

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
            $list = db('Goods')->field('id,fid,name,picname,price,comm,tehui,flash,baoyou')->where($map)->find();
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

            $list['rmb'] = number_format($this->rate*$list['price'],1);  
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
            $list = db('Goods')->field('id,fid,name,picname,image,price,point,content,say,intr')->where($map)->find();
            if (!$list) {
                returnJson('-1','不存在的商品');
            }
            $list['marketPrice'] = $list['price'];
            $list['picname'] = getThumb($list["picname"],200,200);
            $list['picname'] = getRealUrl($list['picname']);

            if ($list['image']=='') {
                $list['image'] = array($list['picname']);            
            }else{
                $list['image'] = explode(",", $list['image']);
            }
            foreach ($list['image'] as $key => $value) {
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
            $ids = db("CouponGoods")->where('goodsID',$list['id'])->value('couponID');
            $map['id'] = array('in',$ids);
            $map['goodsID'] = array('eq','');
            $coupon = db("Coupon")->field('id,name,desc,full,dec,number')->whereOr($map)->select();
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

            returnJson(1,'success',['goods'=>$list,'cartNumber'=>$cartNumber,'fav'=>$fav,'coupon'=>$coupon,'pack'=>$pack,'spec'=>$spec,'filter_spec'=>$filter_spec]);
        }
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