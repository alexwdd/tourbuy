<?php
namespace app\adminx\model;
use think\Session;

class GoodsSpecPrice extends Admin
{  
    //获取列表
    public function getList(){        
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));

        $total = $this->group('key_name')->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->where($map)->order('item_id desc')->limit($firstRow.','.$pageSize)->group('key_name')->select();
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

    //获取单条
    public function find($id){
        $list = $this->get($id);
        if ($list) {
            return $list;
        }else{
            $this->error('信息不存在');
        }
    }


    //更新
    public function edit(array $data = [])
    {
        $res = $this->allowField(true)->save($data,['key_name'=>$data['key_name']]);
        if($res){
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }   

    public function del($id){
        return $this->destroy($id);
    }
}
