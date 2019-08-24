<?php
namespace app\adminx\model;
use think\Session;

class Coupon extends Admin
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

    //获取单条
    public function find($id){
        $list = $this->get($id);
        if ($list) {
            return $list;
        }else{
            $this->error('信息不存在');
        }
    }

    //添加更新数据
    public function saveData( $data )
    {
        if ($data['full'] == 0) {
            $data['desc'] = '立减'.$data['dec'].'元';
        }else{
            $data['desc'] = '满'.$data['full'].'元立减'.$data['dec'].'元';
        }
        if($data['goodsID']!=''){
            $data['goodsID'] = str_replace("，",",",$data['goodsID']);
        }
        if( isset( $data['id']) && !empty($data['id'])) {
            $result = $this->edit( $data );
        } else {
            $result = $this->add( $data );
        }
        return $result;
    }

    //添加
    public function add(array $data = [])
    {
        $validate = validate('Coupon');
        if(!$validate->check($data)) {
            return info($validate->getError());
        }
        $this->allowField(true)->save($data);
        if($this->id > 0){
            if($data['goodsID']!=''){
                $ids = explode(",", $data['goodsID']);
                $arr = [];
                foreach ($ids as $key => $value) {
                    $temp = [
                        'couponID'=>$this->id,
                        'goodsID'=>$value
                    ];
                    array_push($arr,$temp);
                }
                db("CouponGoods")->insertAll($arr);
            }
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }

    //更新
    public function edit(array $data = [])
    {
        $validate = validate('Coupon');
        if(!$validate->check($data)) {
            return info($validate->getError());
        }    
        $this->allowField(true)->save($data,['id'=>$data['id']]);
        if($this->id > 0){
            db("CouponGoods")->where('couponID',$data['id'])->delete();
            if($data['goodsID']!=''){
                $ids = explode(",", $data['goodsID']);
                $arr = [];
                foreach ($ids as $key => $value) {
                    $temp = [
                        'couponID'=>$data['id'],
                        'goodsID'=>$value
                    ];
                    array_push($arr,$temp);
                }
                db("CouponGoods")->insertAll($arr);
            }
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }

    public function del($id){
        return $this->destroy($id);
    }
}
