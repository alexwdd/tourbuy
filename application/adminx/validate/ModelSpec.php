<?php
namespace app\adminx\validate;

use think\Validate;

class ModelSpec extends Validate
{
    protected $rule =   [
        'name'  => 'require',
        'mID'  => 'require',
        'values'  => 'require',
        'sort'  => 'require',
    ];

    protected $message  =   [
        'name.require'      => '规格名称不能为空',
        'mID.require'       => '模型不能为空',
        'values.require'       => '可选值不能为空',
        'sort.require'       => '排序不能为空',
    ];
}