<?php
namespace app\adminx\validate;

use think\Validate;

class Member extends Validate
{

    protected $rule =   [        
        'mobile' => 'require|unique:member',
        'name' => 'require',
        'password' => 'require',
        'depart' => 'require',
        'junxian' => 'require',
    ];

    protected $message  =   [
        'depart.require'       => '请选择部门',
        'name.require'      => '请输入姓名',
        'junxian.require'      => '请选择消防救援衔',
        'mobile.require'       => '手机号不能为空',    
        'password.require'       => '请输入密码'
    ];

    protected $scene = [
        'add' => ['mobile','name','password','depart','junxian'],
        'edit' =>  ['mobile','name','depart','junxian'],
        'password' =>  ['password','oldpwd']
    ];

}


