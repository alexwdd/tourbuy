<?php
namespace app\common\validate;
use think\Validate;

class Address extends Validate
{

    protected $rule =   [ 
        'name' => 'require',
        'tel' => 'require',
        'province' => 'require',
        'city' => 'require',
        'county' => 'require',
        'addressDetail' => 'require'
    ];

    protected $message  =   [
        'name.require'       => '收件人不能为空',
        'tel.require'       => '电话不能为空',
        'province.require'      => '省份不能为空',
        'city.require'      => '城市不能为空',
        'county.require'      => '地区不能为空',
        'addressDetail.require'      => '详细地址不能为空',
    ];
}


