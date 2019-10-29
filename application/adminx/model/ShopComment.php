<?php
namespace app\adminx\model;
use think\Session;

class ShopComment extends Admin
{
    protected $insert = ['createTime'];  

    public function setCreateTimeAttr()
    {
        return time();
    }


    public function getCreateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }


    //获取列表
    public function getList($map){
        
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));
        $field = input('post.field','id');
        $order = input('post.order','desc');
        $keyword = input('post.keyword');
        $status = input('post.status');

        if ($keyword!='') {
            $map['content'] = array('like','%'.$keyword.'%');
        }
        if($status!=''){
            $map['status'] = $status;
        }

        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            foreach ($list as $key => $value) {
                $str = '';
                if($value['images']!=''){
                    $image = explode("|", $value['images']);                    
                    foreach ($image as $k => $val) {
                        $str = '<a href="'.$val.'" target="_blank"><img src="'.$val.'" style="height:30px"/></a>';
                    }
                }
                $list[$key]['images'] = $str;
            }
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

    //添加
    public function add(array $data = [])
    {
        $this->allowField(true)->save($data);
        if($this->id > 0){
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }


    public function del($id){
        return $this->destroy($id);
    }
}
