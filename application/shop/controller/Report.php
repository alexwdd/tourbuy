<?php
namespace app\shop\controller;

class Report extends Admin {

	#列表
	public function index() {
	    if(request()->isPost()){
			$date = input('post.date');
			if ($date=='') {
				$date = date('Y-m-d');
				$starDate = strtotime($date);
				$endDate = strtotime($date);
			}else{
				$date = explode(" - ", $date);
	            $starDate = strtotime($date[0]);
	            $endDate = strtotime($date[1]);
			}

			$arr = [];
			for($i=$endDate; $i>=$starDate; $i-=86400){
				$map['createTime'] = array('between',array($i,$i+86399));
				$map['status'] = array('in',[1,2,3]);
				$map['shopID'] = $this->admin['id'];

				$list = db("Order")->field('total,money,wallet,inprice,discount,payment,payType')->where($map)->select();			
				$total = 0;
				$money = 0;
				$wallet = 0;
				$inprice = 0;
				$payment = 0;
				$discount = 0;
				$lirun = 0;
				$weixin = 0;
				$alipay = 0;
				$dikou = 0;
				foreach ($list as $key => $value) {
					$total += $value['total'];
					$money += $value['money'];
					$wallet += $value['wallet'];
					$discount += $value['discount'];
					$inprice += $value['inprice'];
					$payment += $value['payment'];
					if($payType==1){
						$alipay += $value['money'];
					}elseif($payType==2){
						$weixin += $value['money'];
					}else{
						$dikou += $value['money'];
					}
					$lirun += $value['total'] - $value['wallet'] - $value['inprice']; 
					$lirun = sprintf("%.2f",$lirun);
				}
				array_push($arr,[
					'date'=>date("Y-m-d",$i),
					'number'=>count($list),
					'total'=>$total,
					'money'=>$money,
					'wallet'=>$wallet,
					'weixin'=>$weixin,
					'alipay'=>$alipay,
					'dikou'=>$dikou,
					'inprice'=>$inprice,
					'discount'=>$discount,
					'payment'=>$payment,
					'lirun'=>$lirun,
				]);
			}

			$result = array(
	            'code'=>0,
	            'data'=>$arr,
	            "count"=>count($arr)
	        );
	        echo json_encode($result); 
		}else{
            $this->assign('date', date("Y-m-d"));
			return view();
		}
	}

	public function ajax(){
        if(request()->isPost()){
            $days = input('post.days');            
            $end = date("Y-m-d",time());
            $start = date("Y-m-d",strtotime("-".$days." day"));

            $map['createTime'] = array('between',array(strtotime($start),strtotime($end)+86399));
			$map['shopID'] = $this->admin['id'];
			$list = db('OrderCart')->field('name,sum(number) as num')->where($map)->group('goodsID')->order('num desc')->limit(10)->select();
			foreach ($list as $key => $value) {
				$list[$key]['top'] = 'TOP'.($key+1);
			}
			$result = array(
	            'code'=>0,
	            'data'=>$list,
	            "count"=>count($list)
	        );
	        echo json_encode($result);
        }
    }
}
?>