<?php
namespace app\adminx\model;
use think\Session;

class City extends Admin
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
        $keyword = input('post.keyword');

        $map['id'] = array('gt',0);
        if($keyword!=''){
            $map['name'] = array('like', '%'.$keyword.'%');
        }

        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            foreach ($list as $key => $value) {
                $expressID = db("CityExpress")->where('cityID',$value['id'])->column('expressID');
                $expName = db("Express")->where('id','in',$expressID)->column('name');
                $list[$key]['express'] = implode(",", $expName);
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
        if(!$data['expressID']){
            return info('请选择快递',0);
        }

        foreach ($data['expressID'] as $key => $value) {
            if($data['address'][$key]==''){
                return info('仓库地址不能为空',0);
            }
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
        if ($data['name']=='') {
            return info('请输入名称',0);
        }        
        $data['createTime'] = time();
        $data['updateTime'] = time();
        $this->allowField(true)->save($data);
        if($this->id > 0){
            $this->saveExpress($data['expressID'],$data['address'],$this->id);
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }


    //更新
    public function edit(array $data = [])
    {
        if ($data['name']=='') {
            return info('请输入名称',0);
        }  
        $data['updateTime'] = time();
        $this->allowField(true)->save($data,['id'=>$data['id']]);
        if($this->id > 0){
            $this->saveExpress($data['expressID'],$data['address'],$this->id);
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }

    public function saveExpress($express,$address,$cityID){
        db("CityExpress")->where('cityID',$cityID)->delete();
        $arr = [];
        foreach ($express as $key => $value) {
            array_push($arr,[
                'expressID'=>$value,
                'cityID'=>$cityID,
                'address'=>$address[$key],
            ]);
        }
        db("CityExpress")->insertAll($arr);
    }

    public function del($id){
        return $this->destroy($id);
    }
}
