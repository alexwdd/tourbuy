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
                $list['content'] = htmlspecialchars_decode($list['content']);
                $list['content'] = str_replace("<img src=\"/","<img src=\"http://".$_SERVER['HTTP_HOST']."/",$list['content']);
                returnJson(1,'success',['data'=>$list]);
            }else{
                returnJson(0,'信息不存在');
            }           
        }
    }

    public function article(){
        if (request()->isPost()) {                        
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $id = input('post.id');
            if ($id=='' || !is_numeric($id)) {
                returnJson(0,'参数错误');
            }
            
            $map['id'] = $id;
            $list = db('Article')->field('title,content')->where($map)->find();
            if ($list) {
                $list['content'] = htmlspecialchars_decode($list['content']);
                $list['content'] = str_replace("<img src=\"/","<img src=\"http://".$_SERVER['HTTP_HOST']."/",$list['content']);
                returnJson(1,'success',['data'=>$list]);
            }else{
                returnJson(0,'信息不存在');
            }           
        }
    }	
}