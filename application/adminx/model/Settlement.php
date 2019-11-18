<?php
namespace app\adminx\model;
use think\Session;

class Settlement extends Admin
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
        $keyword = input('post.keyword');
        $createDate = input('post.createDate');

        $map['id'] = array('gt',0);

        if ($createDate!='') {
            $date = explode(" - ", $createDate);
            $startDate = $date[0];
            $endDate = $date[1];
            $map['createTime'] = array('between',array(strtotime($startDate),strtotime($endDate)+86399));
        }

        if ($keyword!='') {
            $map['name'] = array('like','%'.$keyword.'%');;
        }

        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            /*foreach ($list as $key => $value) {
                $list[$key]['cate'] = getMoneyType($value['type']);
            }*/
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

    public function del($id){
        return $this->destroy($id);
    }
}