<?php
namespace app\shop\validate;

use think\Validate;

class Goods extends Validate
{
    protected $rule =   [
        'name'  => 'require',
        'cid'  => 'require',
        'picname'  => 'require',
        //'price'  => 'require',
        'number'  => 'egt:1',
    ];

    protected $message  =   [
        'name.require'      => '名称不能为空',
        'cid.require'       => '分类不能为空',
        //'price.require'       => '销售价不能为空',
        'picname.require'       => '封面不能为空',
        'number.egt'       => '单品数量不能是0'
    ];

}


