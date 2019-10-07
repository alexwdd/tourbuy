<?php
namespace app\api\controller;

use app\common\controller\Base;

class Common extends Base {

	public $user;
	public $flash;    

    public function _initialize(){
    	header('Access-Control-Allow-Origin:*');
        parent::_initialize();

        //检查token
        $token = input('post.token');
        if (!$token) {
            $this->user['id'] = 0;
            $this->user['group'] = 0;
        }
        $map['token'] = $token;
        $map['disable'] = 0;
        $map['token_out'] = array('gt',time());
        $list = db('Member')->where($map)->find();
        if ($list) {
            $this->user = $list;
            $data['token_out'] = time()+3600*config('TOKEN_HOUR');
            $r = db('Member')->where($map)->update($data);
        }else{
            $this->user['id'] = 0;
            $this->user['group'] = 0;
        }

        //今日抢购的商品
        if(cache('flash')){
            $this->flash = cache('flash');
        }else{
            unset($map);
            $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y')); 
            $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            $map['startDate'] = array('elt',$beginToday);
            $map['endDate'] = array('egt',$endToday);
            $flash = db("Flash")->field('goodsID,cityID,group,number,price,spec,pack')->where($map)->order('endDate asc')->select();
            cache('flash',$flash,60);
            $this->flash = $flash;

            unset($map);
            $map['endDate'] = array('lt',time());
            $goodsID = db("Flash")->where($map)->column('goodsID');
            $where['id'] = array('in',$goodsID);
            $where['fid'] = array('in',$goodsID);
            db("Goods")->whereOr($where)->setField('flash',0);
        }
    }

    public function getCouponID($ids,$shopID){
        foreach ($ids as $key => $value) {
            $value = explode("-", $value);
            if($value[0]==$shopID){
                return $value[1];
            }
        }
        return null;
    }

    public function shopQrcode(){
        $shopID = input('param.shopID');
        $url = 'http://m.tourbuy.net/shop?id='.$shopID;
        require_once EXTEND_PATH.'qrcode/qrcode.php';
        $value = input("param.url");//二维码数据
        $errorCorrectionLevel = 'Q';//纠错级别：L、M、Q、H
        $matrixPointSize = 10;//二维码点的大小：1到10
        $object = new \QRcode();
        $object->png($url, false , $errorCorrectionLevel, $matrixPointSize, 2);//
    }

    public function shopQrcode1(){
        $shopID = input('param.shopID');
        $url = 'http://shop.tourbuy.net/?shopID='.$shopID;
        require_once EXTEND_PATH.'qrcode/qrcode.php';
        $value = input("param.url");//二维码数据
        $errorCorrectionLevel = 'Q';//纠错级别：L、M、Q、H
        $matrixPointSize = 10;//二维码点的大小：1到10
        $object = new \QRcode();
        $object->png($url, false , $errorCorrectionLevel, $matrixPointSize, 2);//
    }
}