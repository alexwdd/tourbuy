<?php
namespace app\adminx\model;
use think\Session;

class Shop extends Admin
{
    protected $auto = ['updateTime'];
    protected $insert = ['createTime'];  

    public function setUpdateTimeAttr(){
        return time();
    }

    public function setCreateTimeAttr(){
        return time();
    }

    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
    
    public function getUpdateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function setImageAttr(){       
        $image = input('post.image/a');
        if ($image) {
            return implode(",", input('post.image/a'));
        }else{
            return '';
        } 
    }

    //获取列表
    public function getList(){
        
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));

        $field = input('post.field','id');
        $order = input('post.order','desc');
        $keyword = input('post.keyword');

        $map['id'] = array('gt',0);
        $map['name'] = array('like','%'.$keyword.'%');

        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1); 
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            foreach ($list as $key => $value) {
                $list[$key]['city'] = db("City")->where("id",$value['cityID'])->value("name");
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
        $data['py'] = getfirstchar($data['name']);
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
        $validate = validate('Shop');
        if(!$validate->scene('add')->check($data)) {
            return info($validate->getError());
        }
        if($data['repassword'] != $data['password']){
            return info('两次密码不同');
        }
        $data['password'] = md5($data['password']);
        $this->allowField(true)->save($data);
        if($this->id > 0){
            return info('操作成功',1);
        }else{
            return info('操作失败',0);
        }
    }

    //更新
    public function edit(array $data = [])
    {
        $validate = validate('Shop');
        if(!$validate->scene('edit')->check($data)) {
            return info($validate->getError());
        }
        if($data['password']!=''){
            $data['password'] = MD5($data['password']);
        }else{
            unset($data['password']);
        }
        $this->allowField(true)->save($data,['id'=>$data['id']]);
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
