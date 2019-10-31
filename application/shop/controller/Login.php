<?php
namespace app\shop\controller;

use app\common\controller\Base;
use think\Session;
use think\Loader;
use think\Lang;

class Login extends Base {

    public function _initialize(){
        parent::_initialize();

        lang::load(APP_PATH.'/shop/lang/'.cookie('think_var').'.php');  
    }

    public function index(){
		if( Session::has('shopinfo', 'shop') ) {
			$this->redirect( url('index/index') );
		}
           
		return view();
	}

    public function lang(){
        $lang = input('?get.lang') ? input('get.lang') : 'zh';
        switch ($lang) {
            case 'zh':
                cookie('think_var', 'zh-cn');
                break;
            case 'en':
                cookie('think_var', 'en-us');
                break;
            //其它语言
            default:
                cookie('think_var','zh-cn');
        }
        echo json_encode(['code'=>1]);
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
            $this->error(lang('emptyUsername'));
        }
        elseif ($password=='' || empty($password)) {
            $this->error(lang('emptyPasswrod'));
        }
        elseif ($checkcode=='' || empty($checkcode)) {
            $this->error(lang('emptyVerify'));
        }

        $check = $this->validate(['验证码'=>$checkcode],['验证码'=>'require|captcha']);    
        if ($check!=1) {
            $this->error(lang('verifyError'));
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
        return $this->success(lang('loginSuccess'), url('index/index'));
	}

    function signout(){
        Session::delete('shopinfo','shop');
        cache('access', NULL);
        $this->success(lang('loginOut'),url('login/index'));        
    }
}
