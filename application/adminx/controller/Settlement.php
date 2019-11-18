<?php
namespace app\adminx\controller;

class Settlement extends Admin {

	#列表
	public function index() {
        if (request()->isPost()) {
            $result = model('Settlement')->getList();
            echo json_encode($result);
        }else{
	       return view();
        }
	}

	#添加
    public function pub() {
        if(request()->isPost()){
            set_time_limit(1800);
            $startDate = input('post.startDate');
            $endDate = input('post.endDate');
            if($startDate=='' || $endDate==''){
                $this->error("请选择起止日期");
            }

            if(strtotime($endDate) <= strtotime($startDate)){
                $this->error("起止日期错误");
            }

            $res = db("SettlementLog")->find();
            if($res){
                if(strtotime($startDate) <= strtotime($res['endDate'])){
                    $this->error("起止日期错误，上次结算截止日期".$res['endDate']);
                }                
            }
            $list = db("Shop")->field('id,name,masterEmail')->select();
            foreach ($list as $key => $value) {
                $this->email($value,$startDate,$endDate);
            }

            $fundData = [
                'startDate'=>$startDate,
                'endDate'=>$endDate,
                'createTime'=>time()
            ];

            db("SettlementLog")->insert($fundData);
            $this->success("操作成功");
        }else{
            return view();
        }
    }

    public function email($shop,$startDate,$endDate){
        $start = strtotime($startDate);
        $end = strtotime($endDate)+86399;
        $this->assign('shop',$shop);

        $map['createTime'] = array('between',array($start,$end));
        $map['status'] = array('in',[1,2,3]);
        $map['shopID'] = $shop['id'];
        $order = db("Order")->where($map)->order('id desc')->select();
        $count = [
            'startDate'=>$startDate,
            'endDate'=>$endDate,
            'time'=>date("d/m/Y H:i:s",time()),
            'zhiyouNumber'=>0,
            'zitiNumber'=>0,
            'ziti'=>0,            
            'zhiyou'=>0,
            'money'=>0,
            'insideFee'=>0,
            'discount'=>0,
            'total'=>0
        ];
        foreach ($order as $key => $value) {
            if($value['couponID']>0){
                $order[$key]['coupon'] = db("CouponLog")->where('id',$value['couponID'])->find();
            }

            $goods = db("OrderCart")->where("orderID",$value['id'])->select();
            foreach ($goods as $k => $val) {                
                if($val['jiesuan']==0){
                    $rate = 0;
                }else{
                    $rate = floor(($val['inprice']/$val['jiesuan']*100));
                }
                $goods[$k]['rate'] = $rate;
                if($value['quhuoType']==1){
                    $count['ziti'] += $val['inprice']*$val['trueNumber'];
                }else{
                    $count['zhiyou'] += $val['inprice']*$val['trueNumber'];
                }
            }            
            $order[$key]['goods'] = $goods;

            if($value['quhuoType']==1){
                $count['zitiNumber']++;
            }else{
                $count['zhiyouNumber']++;
            }
            $count['insideFee'] += $value['insideFee'];
            $count['discount'] += $value['discount'];
        }
        $this->assign('order',$order);

        $count['money'] = $count['ziti'] + $count['zhiyou'];
        $count['total'] = $count['money'] + $count['insideFee'] - $count['discount'];
        $this->assign('count',$count);
        $result = $this->fetch('email');

        //保存结算详情
        $count['content'] = $result;
        $count['createTime'] = time();
        $count['shopID'] = $shop['id'];
        $count['name'] = $shop['name'];
        unset($count['time']);
        db('Settlement')->insert($count);

        //发送邮件
        if($shop['masterEmail']!=''){
            $email = $shop['masterEmail'];
            $title = 'TOURBUY SETTLEMENT REPORT FOR '.$shop['name'].' FROM  '.date("d/m/Y",$start).'-'.date("d/m/Y",$end);
            sendEmail($email,$title,$result);
        }
    }

    public function view(){
        $id = input('get.id');
        if ($id!='' || is_numeric($id)) {
            $list = model('Settlement')->find($id);
            if (!$list) {
                $this->error('信息不存在');
            }
        }
        $this->assign('list', $list);
        return view();
    }
}
?>