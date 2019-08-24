<?php
namespace app\api\controller;

class About extends Common
{
    public function index(){
    	if (request()->isPost()) {                        
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $id = input('post.id');
            if ($id=='' || !is_numeric($id)) {
                returnJson(0,'参数错误');
            }
            
            $map['id'] = $id;
            $list = db('Onepage')->field('title,content')->where($map)->find();
            if ($list) {                
                returnJson(1,'success',['data'=>$list]);
            }else{
                returnJson(0,'信息不存在');
            }
           
        }
    }	
}