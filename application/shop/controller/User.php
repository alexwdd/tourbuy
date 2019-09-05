<?php 
namespace app\shop\controller;

class User extends Admin{    

    //修改密码
    function password(){
        if (request()->isPost()){
            $data = input('post.');
            return model('Shop')->password( $data );            
        }else{            
            return view();
        }        
    }
}
?>