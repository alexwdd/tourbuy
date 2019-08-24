<?php
namespace app\common\validate;
use think\Validate;

class Order extends Validate
{
    protected $rule =   [
        'order_no' => 'require|unique:order',
        'tel' => 'require',
        'name' => 'require',
        'addressDetail' => 'require',
    ];

    protected $message  =   [
        'order_no.require'       => '订单号不能为空',
        'order_no.unique'       => '订单号重复',        
        'tel.require'      => '手机号不能为空',
        'name.require'       => '姓名不能为空',
        'addressDetail.require'       => '详细地址不能为空'
    ];
}