<?php
namespace app\api\controller;

class Sender extends Auth {

    //我的购物车
    public function lists(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $keyword = input('param.keyword');
            $page = input('post.page/d',1);
            $pagesize = input('post.pagesize',10);
            $firstRow = $pagesize*($page-1); 

            if($keyword!=''){
                $map['name'] = array('like','%'.$keyword.'%');
            }
            $map['memberID'] = $this->user['id'];
            $obj = db('Sender');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }
            $list = $obj->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            returnJson(1,'success',['next'=>$next,'data'=>$list]);
        }
    }

    public function detail(){
        if (request()->isPost()) {
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $id = input('post.id');
            if ($id=='') {
                returnJson(0,'参数错误');
            }
            $map['id'] = $id;
            $map['memberID'] = $this->user['id'];
            $list = db('Sender')->where( $map )->find();
            if ($list) {  
                returnJson(1,'success',$list); 
            }else{
                returnJson(0,'信息不存在');
            }
        }
    }

    //发布
    public function pub(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $data = input('post.');
            $data['memberID'] = $this->user['id'];
            $res = model('Sender')->saveData( $data );
            if ($res['code']==1) {  
                returnJson(1,'操作成功'); 
            }else{
                returnJson(0,$res['msg']);
            }            
        }
    }

    //删除
    public function del(){
        if (request()->isPost()) { 
            if(!checkFormDate()){returnJson(0,'ERROR');}
            $ids = input('post.ids');
            if ($ids=='') {
                returnJson(0,'缺少参数');
            }
            $ids = explode(",",$ids);
            $map['id'] = array('in',$ids);
            $map['memberID'] = $this->user['id'];
            db("Sender")->where($map)->delete();
            returnJson(1,'success');
        }       
    }
}