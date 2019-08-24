<?php
namespace app\adminx\model;
use think\Session;

class Fund extends Admin
{
    public function getCreateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    //获取列表
    public function getList(){
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));
        $field = input('post.field','id');
        $order = input('post.order','desc');

        $map['id'] = array('gt',0);

        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
        }
        $result = array(
            'code'=>0,
            'data'=>$list,
            "pageNum"=>$pageNum,
            "pageSize"=>$pageSize,
            "pages"=>$pageSize,
            "count"=>$total
        );
        return $result;
    }

    public function del($id){
        return $this->destroy($id);
    }
}
