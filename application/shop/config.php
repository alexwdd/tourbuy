<?php
return [ 
    'page' => [
        'size' => 20,//默认每页记录数
    ],

    //模板布局
    'template' => [
        'layout_on' => true,
        'layout_name' => 'layout',
    ],    

    //权限认证
    "rbac" => [
        'USER_AUTH_ON'=>true,
        'USER_AUTH_TYPE' => 1,        // 默认认证类型 1 登录认证 2 实时认证
        'USER_AUTH_KEY'  =>'authId', // 用户认证SESSION标记
        'ADMIN_AUTH_KEY' =>'administrator',
        'NOT_AUTH_ACTION' => [ // 默认无需认证操作
            'index/index',
            'index/console',
            'index/menu',
            'upload/index',
            'user/password'            
        ]
    ],
    
    //分类模型
    "TABLE_MODEL"=>array(
        0=>array("id"=>1,"name"=>"文章","show"=>1),
        1=>array("id"=>2,"name"=>"商品","show"=>0),
        2=>array("id"=>3,"name"=>"视频","show"=>0),
        3=>array("id"=>4,"name"=>"图片","show"=>0),
        4=>array("id"=>5,"name"=>"下载","show"=>0),
        5=>array("id"=>6,"name"=>"广告","show"=>1),
        6=>array("id"=>7,"name"=>"友情链接","show"=>1),
        7=>array("id"=>8,"name"=>"留言","show"=>1),
    ), 
];