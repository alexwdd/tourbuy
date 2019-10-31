<?php
namespace app\shop\controller;

use app\common\controller\Base;
use think\Loader;
use think\Session;
use think\Request;
use think\lang;

class Admin extends Base {

    public $admin;

    public function _initialize(){
        parent::_initialize();

        lang::load(APP_PATH.'/shop/lang/'.cookie('think_var').'.php');  

        //判断是否已经登录      
        if( !Session::has('shopinfo', 'shop') ) {
            $this->redirect(url('login/index'));
        }
        $user = Session::get('shopinfo', 'shop');
        $this->admin = $user;        
        $this->assign('admin',$this->admin);
    }

    //用户分组
    public function getGroup() {
        $list = db('Role')->field('id,name')->select();
        if($list){
            echo json_encode(array(
                'code'=>1,
                'results'=>array(
                    'data'=>$list
                    )
            ));
        }else{
            $this->error("信息不存在");
        }
    }

    public function getMemberGroup(){
        echo json_encode(array(
            'code'=>1,
            'results'=>array(
                'data'=>config('memberGroup')
                )
        )); 
    }

    public function return_json($results){
        return json_encode(array(
                'code'=>0,
                'results'=>$results
            ));
    }

    public function delFile(){
        if(request()->isPost()){
            $path = input('post.path');
            if($path!=''){
                unlink('.'.$path);
            }
        }
    }
}