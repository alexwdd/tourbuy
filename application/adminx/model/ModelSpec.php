<?php
namespace app\adminx\model;
use think\Session;

class ModelSpec extends Admin
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

        if ($keyword!='') {
            $map['name'] = array('like','%'.$keyword.'%');
        }
        $map['id'] = array('gt',0);
        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            foreach ($list as $key => $value) {
                $item = db('ModelSpecItem')->where(array('specID'=>$value['id']))->column('item');
                $item = implode(",", $item);
                $list[$key]['item'] = $item;
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
        $validate = validate('ModelSpec');
        if(!$validate->check($data)) {
            return info($validate->getError());
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
        $this->allowField(true)->save($data);
        if($this->id > 0){ 
            $itemData = [];
            $itemArr = explode("\n", trim($data['values']));
            for ($i=0; $i <count($itemArr) ; $i++) {
                if ($itemArr[$i]!='') {
                    array_push($itemData, ['specID'=>$this->id,'item'=>$itemArr[$i]]);
                }                
            }
            db('ModelSpecItem')->insertAll($itemData);
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }
    //更新
    public function edit(array $data = [])
    {
        $this->allowField(true)->save($data,['id'=>$data['id']]);
        if($this->id > 0){
            $itemData = [];
            $itemArr = explode("\n", trim($data['values']));
            for ($i=0; $i <count($itemArr) ; $i++) {
                if ($itemArr[$i]!='') {
                    array_push($itemData, ['specID'=>$this->id,'item'=>$itemArr[$i]]);
                }                
            }
            db('ModelSpecItem')->where(array('specID'=>$data['id']))->delete();
            db('ModelSpecItem')->insertAll($itemData);
            return info('操作成功',1);
        }else{            
            return info('操作失败',0);
        }
    }   

    public function del($id){
        return $this->destroy($id);
    }
}
