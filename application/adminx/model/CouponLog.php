<?php
namespace app\adminx\model;
use think\Session;

class CouponLog extends Admin
{
    protected $auto = ['updateTime'];
    protected $insert = ['createTime'];  

    public function setUpdateTimeAttr()
    {
        return time();
    }

    public function setCreateTimeAttr()
    {
        return time();
    }


    public function getCreateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    public function getUpdateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    //获取列表
    public function getList($map){
        
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));

        $field = input('post.field','id');
        $order = input('post.order','desc');

        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            foreach ($list as $key => $value) {
                if($value['useTime']>0){
                    $list[$key]['useTime'] = date("Y-m-d H:i:s",$value['useTime']);
                }else{
                    $list[$key]['useTime'] = '-';
                }
                if($value['endTime']>0){
                    $list[$key]['endTime'] = date("Y-m-d H:i:s",$value['endTime']);
                }else{
                    $list[$key]['endTime'] = '-';
                }
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
        $validate = validate('CouponLog');
        if(!$validate->check($data)) {
            return info($validate->getError());
        }
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
