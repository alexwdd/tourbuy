<?php
namespace app\api\controller;

use app\common\controller\Base;

class Test extends Base {

    public function index(){
        $content = $this->fetch("email/notify");
        echo $content;die;
        $email = '20779512@qq.com';
        $title = 'Hello ABC! You have a new order from tourbuy';
        sendEmail($email,$title,$content);
    }

}