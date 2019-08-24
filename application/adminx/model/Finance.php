<?php
namespace app\adminx\model;
use think\Session;

class Finance extends Admin
{
   
    public function getCreateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public function getShowTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    //获取列表
    public function getList($moneyType=null){        
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));
        $field = input('post.field','id');
        $order = input('post.order','desc');
        $keyword = input('post.keyword');
        $createDate = input('post.createDate');
        $type = input('post.type');

        $map['id'] = array('gt',0);

        if ($createDate!='') {
            $date = explode(" - ", $createDate);
            $startDate = $date[0];
            $endDate = $date[1];
            $map['createTime'] = array('between',array(strtotime($startDate),strtotime($endDate)+86399));
        }

        if($moneyType){
            $map['type'] = $moneyType;
        }

        if ($type!='') {
            $map['type'] = $type;
        }

        if ($keyword!='') {
            $map['memberID'] = $keyword;
        }

        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            foreach ($list as $key => $value) {
                $list[$key]['cate'] = getMoneyType($value['type']);
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


    public function del($id){
        return $this->destroy($id);
    }
}