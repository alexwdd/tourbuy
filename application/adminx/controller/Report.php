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
				$endDate = strtotime($date);
				$start = date("Y-m-d",strtotime("-7 day"));
				$starDate = strtotime($start);
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
				if($cityID!=''){
					$map['shopID'] = array('in',$ids);
				}
				$list = db("Order")->field('total,money,wallet,inprice,ztInprice,discount,payment,payType')->where($map)->select();			
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
					if($value['quhuoType']==0){
						$inprice += $value['inprice'];
					}else{
						$inprice += $value['ztInprice'];
					}					
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
            $city = db('City')->field('id,name')->select();
            $this->assign('city', $city);
            $start = date("Y-m-d",strtotime("-7 day"));
            $this->assign('start',$start);
            $this->assign('date', date("Y-m-d"));
			return view();
		}
	}

	public function goods(){
        if(request()->isPost()){
            $date = input('post.date');
			$cityID = input('post.cityID');
			$shopID = input('post.shopID');
			if ($date=='') {
				$date = date('Y-m-d');
				$endDate = strtotime($date);
				$start = date("Y-m-d",strtotime("-7 day"));
				$starDate = strtotime($start);
			}else{
				$date = explode(" - ", $date);
	            $starDate = strtotime($date[0]);
	            $endDate = strtotime($date[1]);
			}

			if($shopID!=''){
				$map['shopID'] = $shopID;
			}
			if($cityID!=''){
				$map['cityID'] = $cityID;
			}

            $map['createTime'] = array('between',array($starDate,$endDate+86399));
			$list = db('OrderCart')->field('name,sum(number) as num')->where($map)->group('goodsID')->order('num desc')->limit(20)->select();
			foreach ($list as $key => $value) {
				$list[$key]['top'] = 'TOP'.($key+1);
			}
			$result = array(
	            'code'=>0,
	            'data'=>$list,
	            "count"=>count($list)
	        );
	        echo json_encode($result);
        }else{
            $city = db('City')->field('id,name')->select();
            $this->assign('city', $city);
            $start = date("Y-m-d",strtotime("-7 day"));
            $this->assign('start',$start);
            $this->assign('date', date("Y-m-d"));
			return view();
        }
    }

    //店铺销量
    public function shop(){
        if(request()->isPost()){
            $date = input('post.date');
			$cityID = input('post.cityID');

			if ($date!='') {
				$date = explode(" - ", $date);
	            $starDate = strtotime($date[0]);
	            $endDate = strtotime($date[1]);
	            $map['createTime'] = array('between',array($starDate,$endDate+86399));
			}
			if($cityID!=''){
				$map['cityID'] = $cityID;
			}
			$map['status'] = array('in',[1,2,3]);
			$list = db('Order')->field('shopID,sum(total) as num')->where($map)->group('shopID')->order('num desc')->select();
			foreach ($list as $key => $value) {
				$list[$key]['top'] = 'TOP'.($key+1);
				$list[$key]['name'] = db("Shop")->where('id',$value['shopID'])->value('name');
			}
			$result = array(
	            'code'=>0,
	            'data'=>$list,
	            "count"=>count($list)
	        );
	        echo json_encode($result);
        }else{
            $city = db('City')->field('id,name')->select();
            $this->assign('city', $city);
			return view();
        }
    }

    //店铺销量
    public function team(){
        if(request()->isPost()){
            $date = input('post.date');
			
			$list = db("Member")->field('id,nickname,headimg')->where('team',1)->select();
			foreach ($list as $key => $value) {
				unset($map);
				if ($date!='') {
					$date = explode(" - ", $date);
		            $starDate = strtotime($date[0]);
		            $endDate = strtotime($date[1]);
		            $map['createTime'] = array('between',array($starDate,$endDate+86399));
				}
				$map['tjID'] = $value['id'];
				$list[$key]['pushNumber'] = db("Member")->where($map)->count();

				$map['status'] = array('in',[1,2,3]);
				$list[$key]['total'] = db("Order")->where($map)->sum('total');
				$list[$key]['bonus'] = db("Order")->where($map)->sum('bonus');
			}
			$result = array(
	            'code'=>0,
	            'data'=>$list,
	            "count"=>count($list)
	        );
	        echo json_encode($result);
        }else{
			return view();
        }
    }

    public function ajax(){
    	$map['status'] = array('in',[1,2,3]);
    	$map['tjID'] = 0;
    	$total1 = db("Order")->where($map)->sum('total');
    	$map['tjID'] = array('gt',0);
    	$total2 = db("Order")->where($map)->sum('total');

    	$data = [
    		['name'=>'散客订单','value'=>$total1],
    		['name'=>'推广订单','value'=>$total2],
    	];
    	echo json_encode($data);
    }
}
?>