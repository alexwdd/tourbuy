<?php
namespace app\common\model;
use think\Model;

class Order extends Model
{
   
    public function add($data){
        $validate = validate('Order');
        if(!$validate->check($data)) {
            return array('code'=>0,'msg'=>$validate->getError());
        }
        $data['createTime'] = time();
        if($data['isCut']==1){
            $data['endTime'] = 0;
        }else{
            $data['endTime'] = time();
        }        
        $data['status'] = 0;
        $data['payType'] = 0;
        $data['payStatus'] = 0;
        $this->allowField(true)->save($data);
        if($this->id > 0){ 
            return array('code'=>1,'msg'=>$this->id);
        }else{
            return array('code'=>0,'msg'=>'操作失败');
        }     
    }
}