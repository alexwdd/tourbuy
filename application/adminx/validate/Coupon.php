<?php
namespace app\adminx\validate;

use think\Validate;

class Coupon extends Validate
{
    protected $rule =   [
        'name'  => 'require',
        'dec'  => 'require',
        'day'  => 'require',
    ];

    protected $message  =   [
        'name.require'      => '名称不能为空',
        'dec.require'      => '请输入金额',
        'day.require'      => '请输入有效期',
    ];
}