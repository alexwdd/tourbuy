<?php
namespace app\adminx\validate;

use think\Validate;

class Junxian extends Validate
{
    protected $rule =   [
        'name'  => 'require'
    ];

    protected $message  =   [
        'name.require'      => '名称不能为空'
    ];
}