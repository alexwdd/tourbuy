<?php
namespace app\adminx\model;

class Goods extends Admin
{
    protected $auto = ['updateTime','cid','path','cid1','path1','image','comm','jingpin','tehui','baoyou'];
    protected $insert = ['createTime'];  

    public function setUpdateTimeAttr(){
        return time();
    }
    public function setCreateTimeAttr(){
        return time();
    }
    public function setCidAttr(){
        $class = explode(',', input('post.cid'));
        return $class[0];
    }
    public function setPathAttr(){        
        $class = explode(',', input('post.cid'));
        return $class[1];
    }
    public function setCid1Attr(){
        if (input('post.cid1')!='') {
            $class = explode(',', input('post.cid1'));
            return $class[0];
        }else{
            return 0;
        }        
    }
    public function setPath1Attr(){   
        if (input('post.cid1')!='') {
            $class = explode(',', input('post.cid1'));
            return $class[1];
        }else{
            return '';
        }
    }
    public function setImageAttr(){       
        $image = input('post.image/a');
        if ($image) {
            return implode(",", input('post.image/a'));
        }        
    }
    public function getCreateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
    public function getUpdateTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }
    public function setJingpinAttr(){        
        if(input('post.jingpin')==''){return 0;}else{return 1;}
    }
    public function setCommAttr(){        
        if(input('post.comm')==''){return 0;}else{return 1;}
    }
    public function setBaoyouAttr(){        
        if(input('post.baoyou')==''){return 0;}else{return 1;}
    }
    public function setTehuiAttr(){        
        if(input('post.tehui')==''){return 0;}else{return 1;}
    }

    //获取列表
    public function getList(){
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));
        $field = input('post.field','id');
        $order = input('post.order','desc');
        $path = input('path');
        $keyword  = input('keyword');
        $type  = input('type');
        $shopID  = input('shopID');

        if($path!=''){
            $map['path'] = array('like', $path.'%');
        }
        if($keyword!=''){
            $map['name|short|keyword'] = array('like', '%'.$keyword.'%');
        }
        if($type!=''){
            $map['show'] = $type;
        }
        if($shopID!=''){
            $map['shopID'] = $shopID;
        }
        $map['fid'] = 0;
        
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
        $shop = db('Shop')->where('id',$data['shopID'])->find();
        $data['shopName'] = $shop['name'];
        $data['cityID'] = $shop['cityID'];
        $data['group'] = $shop['group'];
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
        $validate = validate('Goods');
        if(!$validate->check($data)) {
            return info($validate->getError());
        }
        $this->allowField(true)->save($data);
        if($this->id > 0){
            $data['id'] = $this->id;
            return info('操作成功',1,'',$data);
        }else{
            return info('操作失败',0);
        }
    }
    //更新
    public function edit(array $data = [])
    {
        $validate = validate('Goods');
        if(!$validate->check($data)) {
            return info($validate->getError());
        }    
        $this->allowField(true)->save($data,['id'=>$data['id']]);
        if($this->id > 0){
            return info('操作成功',1,'',$data);
        }else{
            return info('操作失败',0);
        }
    }

    //删除
    public function del($id){
        return $this->destroy($id);
    }

    /**
     * 后置操作方法
     * 自定义的一个函数 用于数据保存后做的相应处理操作, 使用时手动调用
     * @param int $goods_id 商品id
     */
    public function afterSave($goods_id){         
        // 商品规格价钱处理
        $goods_item = input('item/a');
        $eidt_goods_id = input('goods_id',0);
        $specStock = db('GoodsSpecPrice')->where('goods_id = '.$goods_id)->column('key,key_name');
        if ($goods_item) {            
            $keyArr = '';//规格key数组
            foreach ($goods_item as $k => $v) {           
                $keyArr .= $k.',';
                // 批量添加数据
                $v['price'] = trim($v['price']);
                $store_count = $v['store_count'] = trim($v['store_count']); // 记录商品总库存
                $v['weight'] = trim($v['weight']);
                $data = [
                    'goods_id' => $goods_id,
                    'key' => $k,
                    'key_name' => $v['key_name'],
                    'price' => $v['price'],
                    'store_count' => $v['store_count'],
                    'weight' => $v['weight'],
                    'spec_img' => $v['spec_img'],
                ];                
                if (!empty($specStock[$k])) {
                    db('GoodsSpecPrice')->where(['goods_id' => $goods_id, 'key' => $k])->update($data);
                } else {
                    db('GoodsSpecPrice')->insert($data);
                }
            }
            if($keyArr){
                db('GoodsSpecPrice')->where('goods_id',$goods_id)->whereNotIn('key',$keyArr)->delete();
            }
        }

        //处理套餐
        $base = db('Goods')->where('id',$goods_id)->find();
        if($base){
            unset($base['id']);
            $pack_id = input("post.pack_id/a");
            $pack_name = input("post.pack_name/a");
            $pack_price = input("post.pack_price/a");
            $pack_number = input("post.pack_number/a");
            $pack_data = [];
            for ($i=0; $i <count($pack_name) ; $i++) { 
                if($pack_name[$i]!=''){  
                    $temp = $base;
                    $temp['fid'] = $goods_id;
                    $temp['name'] = $pack_name[$i];
                    $temp['price'] = $pack_price[$i];       
                    $temp['number'] = $pack_number[$i];
                    array_push($pack_data,$temp);
                    if (!empty($pack_id[$i])) {
                        db('Goods')->where(['fid' => $goods_id, 'id' => $pack_id[$i]])->update($temp);
                    } else {
                        db('Goods')->insert($temp);
                    }
                }
            }
        }         
    }
}
