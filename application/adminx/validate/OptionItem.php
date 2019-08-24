<?php
namespace app\adminx\validate;

use think\Validate;

class OptionItem extends Validate
{
    protected $rule =   [
        'cate'  => 'require',
        'name'  => 'require'
    ];

    protected $message  =   [
        'name.cate'      => '分类不能为空',
        'name.require'      => '名称不能为空',
    ];
}