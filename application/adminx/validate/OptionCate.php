<?php
namespace app\adminx\validate;

use think\Validate;

class OptionCate extends Validate
{
    protected $rule =   [
        'name'  => 'require'
    ];

    protected $message  =   [
        'name.require'      => '名称不能为空',
    ];
}