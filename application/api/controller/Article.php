<?php
namespace app\api\controller;

class Article extends Common {

    public function getcate(){
        if(request()->isPost()){
        	if(!checkFormDate()){returnJson(0,'ERROR');}
        	$map['model'] = 1;
        	$list = db("Category")->field('id,name')->where($map)->order('sort asc,id asc')->select();
        	returnJson(1,'success',$list);
        }
    }

    public function getNoRead(){
        $token = input('post.token');
        if (!$token) {
            return 0;
        }
        $map['token'] = $token;
        $map['disable'] = 0;
        $map['token_out'] = array('gt',time());
        $list = db('Member')->where($map)->find();
        if ($list) {
            unset($map);
            $map['memberID'] = $list['id'];
            $map['read'] = 0;
            $count = db("Message")->where($map)->count();
            return $count;
        }else{
            return 0;
        }
    }

    public function getlist(){
    	if(request()->isPost()){
        	if(!checkFormDate()){returnJson(0,'ERROR');}

            $cid = input('post.cid');
        	$keyword = input('post.keyword');
            $page = input('post.page',1);
            if ($cid!=0 && is_numeric($cid)) {
            	$map['cid'] = $cid;
            }
            if ($keyword!='') {
                $map['title'] = array('like','%'.$keyword.'%');
            }
            $page = input('post.page/d',1); 
            $pagesize =10;
            $firstRow = $pagesize*($page-1); 

            $map['del'] = 0;
            $map['status'] = 1;
            $obj = db('Article');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }

            $list = $obj->field('id,title,picname,hit,createTime')->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            foreach ($list as $key => $value) {
                if ($value['picname']!='') {
                    $image = explode(",", $value['picname']);
                    foreach ($image as $k => $val) {
                        $image[$k] = getRealUrl($val);
                    }
                    $list[$key]['picname'] = $image;
                    if (count($image)>1) {
                        $list[$key]['image'] = 1;
                    }else{
                        $list[$key]['image'] = 0;
                    }
                }
                $list[$key]['createTime'] = date("Y-m-d",$value['createTime']);
            }
            
            returnJson(1,'success',['next'=>$next,'data'=>$list]);
        }
    }

    public function view(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $id = input('post.id');
            if ($id=='' || !is_numeric($id)) {
                returnJson(0,'参数错误');
            }
            
            $map['id'] = $id;
            $map['del'] = 0;
            $map['status'] = 1;
            $list = db('Article')->where($map)->find();
            if ($list) {
                db('Article')->where($map)->setInc('hit');
                $list['createTime'] = date("Y-m-d",$list['createTime']);

                $list['like'] = db("ArticleDigg")->where('articleID',$list['id'])->count();

                unset($map);
                $map['id'] = array('neq',$list['id']);
                $map['status'] = 1;
                $map['del'] = 0;
                $map['cid'] = $list['cid'];
                $about = db("Article")->where($map)->field('id,title,picname,hit,createTime')->limit(5)->order('id desc')->select();
                foreach ($about as $key => $value) {
                    if ($value['picname']!='') {
                        $image = explode(",", $value['picname']);
                        foreach ($image as $k => $val) {
                            $image[$k] = getRealUrl($val);
                        }
                        $about[$key]['picname'] = $image;
                        if (count($image)>1) {
                            $about[$key]['image'] = 1;
                        }else{
                            $about[$key]['image'] = 0;
                        }
                    }
                    $about[$key]['createTime'] = date("Y-m-d",$value['createTime']);
                }


                $token = input('post.token');
                if (!$token) {
                    $fav = '收藏';
                }
                unset($map);
                $map['token'] = $token;
                $map['disable'] = 0;
                $map['token_out'] = array('gt',time());
                $user = db('Member')->where($map)->find();
                if ($user) {
                    unset($map);
                    $map['memberID'] = $user['id'];
                    $map['articleID'] = $list['id'];
                    $count = db("ArticleFav")->where($map)->count();
                    if ($count>0) {
                        $fav = '已收藏';
                    }else{
                        $fav = '收藏';
                    }
                    
                }else{
                    $fav = '收藏';
                }
                returnJson(1,'success',['data'=>$list,'about'=>$about,'fav'=>$fav]);
            }else{
                returnJson(0,'信息不存在');
            }
        }
    }

    public function comment(){
        if(request()->isPost()){
            if(!checkFormDate()){returnJson(0,'ERROR');}

            $articleID = input('post.articleID');
            $page = input('post.page',1);

            if ($articleID=='' && !is_numeric($articleID)) {
                returnJson(0,'参数错误');
            }
            $page = input('post.page/d',1); 
            $pagesize =10;
            $firstRow = $pagesize*($page-1); 

            $map['status'] = 1;
            $map['articleID'] = $articleID;
            $obj = db('ArticleComment');
            $count = $obj->where($map)->count();
            $totalPage = ceil($count/$pagesize);
            if ($page < $totalPage) {
                $next = 1;
            }else{
                $next = 0;
            }

            $list = $obj->where($map)->limit($firstRow.','.$pagesize)->order('id desc')->select();
            foreach ($list as $key => $value) {                
                $list[$key]['createTime'] = getLastTime($value['createTime']);
            }
            
            returnJson(1,'success',['next'=>$next,'data'=>$list]);
        }
    }
}