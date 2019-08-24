<?php
namespace app\adminx\validate;

use think\Validate;

class Shop extends Validate
{
    protected $rule =   [
        'account' => 'require|unique:shop',
        'password' => 'require|length:6,12',
        'name'  => 'require',
        'linkman'  => 'require',
        'address'  => 'require',
        'tel'  => 'require',
    ];

    protected $message  =   [
        'account.require'      => '请输入登录账号',
        'account.unique'      => '账号重复',
        'password.require'       => '请输入密码',
        'password.length'       => '密码应为6-12位',
        'name.require'       => '商铺名称不能为空',
        'linkman.require'       => '联系人不能为空',
        'address.require'       => '地址不能为空',
        'tel.require'       => '联系电话不能为空',
    ];

    protected $scene = [
        'add' => ['account','password', 'name', 'address', 'tel', 'linkman'],
        'edit' => ['name', 'address', 'tel', 'linkman']
    ];
}