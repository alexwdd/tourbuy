<?php
namespace app\adminx\controller;

use app\common\controller\Base;
use think\Loader;
use think\Session;
use think\Request;

class Admin extends Base {

    public $admin;

    public function _initialize(){
        parent::_initialize();

        //判断是否已经登录      
        if( !Session::has('userinfo', 'admin') ) {
            $this->redirect(url('login/index'));
        }
        $user = Session::get('userinfo', 'admin');
        $this->admin = $user;        
        $request = Request::instance();
        $action_url = $request->controller().'/'.$request->action();        
        if($user['administrator']!=1 && !$this->checkRule($user, $action_url)) {
            $this->error('您没有访问权限');
        }
        $this->assign('admin',$this->admin);
    }

    //验证权限
    public function checkRule($user, $action_url)
    {
        $node = Loader::model('Node');
        if($node->isCheck($user,$action_url)) {
            return true;
        }        
        return false;
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