<?php
namespace app\adminx\model;
use think\Session;

class Order extends Admin
{
    public function getCreateTimeAttr($value)
    {
        return date("Y-m-d H:i:s",$value);
    }

    //获取列表
    public function getList($map=null){
        $pageNum = input('post.page',1);
        $pageSize = input('post.limit',config('page.size'));
        $field = input('post.field','id');
        $order = input('post.order','desc');
        $status = input('post.status');        
        $keyword = input('post.keyword');
        $tjID = input('post.tjID');
        $payType = input('post.payType');
        $send = input('post.send');
        $cityID = input('post.cityID');
        $shopID = input('post.shopID');
        $order_no = input('post.order_no');
        $createDate = input('post.createDate');

        if ($status!='') {
            $map['status'] = $status;
        }        
        if ($payType!='') {
            $map['payType'] = $payType;
        }
        if ($tjID!='') {
            $map['tjID'] = $tjID;
        }
        if ($cityID!='') {
            $map['cityID'] = $cityID;
        }
        if ($shopID!='') {
            $map['shopID'] = $shopID;
        }
        if ($send!='') {
            $map['send'] = $send;
        }
        if ($keyword!='') {
            $map['name|sender'] = $keyword;
        }
        if ($order_no!='') {
            $map['order_no'] = $order_no;
        }

        if ($createDate!='') {
            $date = explode(" - ", $createDate);
            $startDate = $date[0];
            $endDate = $date[1];
            $map['createTime'] = array('between',array(strtotime($startDate),strtotime($endDate)+86399));
        }

        $total = $this->where($map)->count();
        $pages = ceil($total/$pageSize);
        $firstRow = $pageSize*($pageNum-1);         
        $list = $this->where($map)->order($field.' '.$order)->limit($firstRow.','.$pageSize)->select();
        if($list) {
            $list = collection($list)->toArray();
            foreach ($list as $key => $value) {
                if ($value['couponID']>0) {                    
                    $list[$key]['coupon'] = db("CouponLog")->where("id=".$value['couponID'])->value("name");   
                }
                if($value['send']==0 && $value['createDate']<(time()-172800)){
                    $list[$key]['flag'] = 1;
                }
                $list[$key]['shopName'] = db("Shop")->where('id',$value['shopID'])->value("name");
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
        $validate = validate('Article');
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
    //更新
    public function edit(array $data = [])
    {
        $validate = validate('Article');
        if(!$validate->check($data)) {
            return info($validate->getError());
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

    //html过滤
    protected function cutstr_html($string, $sublen){
        $string = strip_tags($string);
        $string = preg_replace ('/\n/is', '', $string);
        $string = preg_replace ('/ |　/is', '', $string);
        $string = preg_replace ('/&nbsp;/is', '', $string);   
        preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $string, $t_string);   
        if(count($t_string[0]) - 0 > $sublen) $string = join('', array_slice($t_string[0], 0, $sublen))."…";   
        else $string = join('', array_slice($t_string[0], 0, $sublen));   
        return $string;
    }
}
