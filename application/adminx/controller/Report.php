<?php
namespace app\adminx\controller;
use think\Cache;

class Report extends Admin {

	#列表
	public function index() {
	    if(request()->isPost()){
			$date = input('post.date');
			$cityID = input('post.cityID');
			$shopID = input('post.shopID');
			if ($date=='') {
				$date = date('Y-m-d');
				$starDate = strtotime($date);
				$endDate = strtotime($date);
			}else{
				$date = explode(" - ", $date);
	            $starDate = strtotime($date[0]);
	            $endDate = strtotime($date[1]);
			}

			if($cityID!=''){
				$ids = db("Shop")->where('cityID',$cityID)->column('id');
			}

			$arr = [];
			for($i=$endDate; $i>=$starDate; $i-=86400){
				$map['createTime'] = array('between',array($i,$i+86399));
				$map['status'] = array('in',[1,2,3]);

				if($shopID!=''){
					$map['shopID'] = $shopID;
				}
				if($ids){
					$map['shopID'] = array('in',$ids);
				}
				$list = db("Order")->field('total,wallet,money,inprice,discount,payment,payType')->where($map)->select();			
				$total = 0;
				$wallet = 0;
				$money = 0;
				$inprice = 0;
				$payment = 0;
				$discount = 0;
				$lirun = 0;
				$weixin = 0;
				$alipay = 0;
				foreach ($list as $key => $value) {
					$total += $value['total'];
					$wallet += $value['wallet'];
					$discount += $value['discount'];
					$money += $value['money'];
					$inprice += $value['inprice'];
					$payment += $value['payment'];
					if($payType==1){
						$alipay += $value['money'];
					}else{
						$weixin += $value['money'];
					}
					$lirun += $value['money'] - $value['inprice']; 
				}
				array_push($arr,[
					'date'=>date("Y-m-d",$i),
					'number'=>count($list),
					'total'=>$total,
					'wallet'=>$wallet,
					'money'=>$money,
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
			$shop = db('Shop')->field('id,name')->order("py asc")->select();
            $this->assign('shop', $shop);

            $city = db('City')->field('id,name')->select();
            $this->assign('city', $city);
            $this->assign('date', date("Y-m-d"));
			return view();
		}
	}
}
?>