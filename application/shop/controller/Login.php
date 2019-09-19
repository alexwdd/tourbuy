<?php
namespace app\shop\controller;

use app\common\controller\Base;
use think\Session;
use think\Loader;

class Login extends Base {

    public function index(){
		if( Session::has('shopinfo', 'shop') ) {
			$this->redirect( url('index/index') );
		}        
		return view();
	}

	//验证码显示
    public function verify() {
        ob_clean();
        $config =    [
            'fontSize'    =>    100,    
            'length'      =>    4,   
            'useCurve'=>false,
            'codeSet'=>'0123456789'
        ];
        $captcha = new \think\captcha\Captcha($config);
        return $captcha->entry();
    }

	public function doLogin() {
		$username = input('post.username');
        $password = input('post.password');
        $checkcode = input('post.checkcode');
        $safecode = input('post.safecode');

        if ($username=='' || empty($username)) {
            $this->error('请输入用户名');
        }
        elseif ($password=='' || empty($password)) {
            $this->error('请输入密码');
        }
        elseif ($checkcode=='' || empty($checkcode)) {
            $this->error('请输入验证码');
        }

        $check = $this->validate(['验证码'=>$checkcode],['验证码'=>'require|captcha']);    
        if ($check!=1) {
            $this->error($check);
        }

        $loginData = array(
            'account'=>$username,
            'password'=>$password
        );
        
        $res = Loader::model('Shop')->login( $loginData );
        if ($res['code'] !== 1) {
            return $this->error( $res['msg'] );
        }
        unset($res['data']['password']);
        $res['data']['administrator'] = 1;

        Session::set('shopinfo', $res['data'], 'shop');
        return $this->success('登录成功', url('index/index'));
	}

    function signout(){
        Session::delete('shopinfo','shop');
        cache('access', NULL);
        $this->success('成功退出',url('login/index'));        
    }
}