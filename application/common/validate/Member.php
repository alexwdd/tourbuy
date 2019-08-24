<?php
namespace app\common\validate;

use think\Validate;

class Member extends Validate
{

    protected $rule =   [
        'openid' => 'require|unique:member',
        'mobile' => 'require|unique:member',
        'password' => 'require|length:6,20',
    ];

    protected $message  =   [
        'mobile.require'       => '手机号不能为空',
        'mobile.unique'       => '手机号重复',        
        'password.require'       => '请输入密码',
        'password.length'       => '密码应为6-20位',
        'openid.require'       => 'openid不能为空',
        'openid.unique'       => 'openid重复',  
    ];

    protected $scene = [
        'add' => ['mobile', 'password'],
        'wechat' => ['openid'],
        'login' =>  ['account','password','checkcode'],
        'password' =>  ['password','oldpwd'],
        'getpwd' =>  ['password','payPassword','checkcode'],
    ];

}


