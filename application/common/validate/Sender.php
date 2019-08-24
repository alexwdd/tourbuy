<?php
namespace app\common\validate;
use think\Validate;

class Sender extends Validate
{

    protected $rule =   [ 
        'name' => 'require',
        'tel' => 'require'     
    ];

    protected $message  =   [
        'name.require'       => '收件人不能为空',
        'tel.require'       => '电话不能为空'      
    ];
}


