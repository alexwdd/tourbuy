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
        }

        //今日抢购的商品
        unset($map);
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y')); 
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $map['startDate'] = array('elt',$beginToday);
        $map['endDate'] = array('egt',$endToday);
        $this->flash = db("Flash")->cache(true,60)->field('goodsID,number,price,spec,pack')->where($map)->order('endDate asc')->select();
    }

    /*public function base64ToImg($path , $name , $data){
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $data, $result)){
            $type = $result[2];
            if(!in_array($type,array("jpg","png","bmp","jpeg","gif"))){
                return false;
            }
            $new_file = $path.date('Ym',time())."/";            
            if(!file_exists($new_file)){
                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir('../'.$new_file, 0700, true);
            }
            $new_file = $new_file.$name.".{$type}";  
            if (file_put_contents('..'.$new_file, base64_decode(str_replace($result[1], '', $data)))){
                return $new_file;
            }else{
                return false;
            }
        }
    }*/
}