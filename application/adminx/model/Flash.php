<?php
namespace app\adminx\model;
use think\Session;

class Flash extends Admin
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


    public function getStartDateAttr($value)
    {
        return date("Y-m-d",$value);
    }

    public function getEndDateAttr($value)
    {
        return date("Y-m-d",$value);
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
            foreach ($list as $key => $value) {
                $spec = unserialize($value['spec']);
                $temp = '';
                foreach ($spec as $k => $val) {
                    $temp .= '<span>'.$val['name'].' 价格：'.$val['price'].'</span> | ';
                }
                $list[$key]['spec'] = $temp;

                $temp = '';
                $pack = unserialize($value['pack']);
                foreach ($pack as $k => $val) {
                    $temp .= '<span>'.$val['name'].' 价格：'.$val['price'].'</span> | ';
                }
                $list[$key]['pack'] = $temp;
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
        $spec = [];
        foreach ($data['spec_id'] as $key => $value) {
            array_push($spec,['specID'=>$value,'name'=>$data['spec_name'][$key],'price'=>$data['spec_price'][$key]]);
        }
        $data['spec'] = serialize($spec);

        $pack = [];
        foreach ($data['pack_id'] as $key => $value) {
            array_push($pack,['packID'=>$value,'name'=>$data['pack_name'][$key],'price'=>$data['pack_price'][$key]]);
        }
        $data['pack'] = serialize($pack);

        $date = explode(" - ", $data['date']);
        $data['startDate'] = strtotime($date[0]);
        $data['endDate']  = strtotime($date[1])+86399;

        $goods = db("Goods")->where('id',$data['goodsID'])->find();
        $temp = explode("-",$goods['path']);
        $data['cid'] = $temp[1];
        $data['goodsName'] = $goods['name'];
        $data['cityID'] = $goods['cityID'];
        $data['group'] = $goods['group'];
        $data['shopID'] = $goods['shopID'];

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
