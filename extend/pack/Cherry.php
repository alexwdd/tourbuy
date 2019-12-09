<?php
namespace pack;

class Cherry {

	private $cart;				//购物车商品
	private $baoguoArr = [];
	private $province;
	private $extendArea = [];
	private $maxNumber = 10; 	//单个包裹中最多商品个数
	private $maxWeight = 4; 	//单个包裹最大重量(kg)
	private $maxPrice = 200; 	//单个包裹最大金额
	private $express = [];

	/*
	$cart中的trueNumber是实际单品数量，比如商品A单品数量是3个，如果购物车中有2个，单品数量总数是6，这里的trueNumber不是数据库中单个商品的trueNumber！！！
	包裹的status属性如果是1就是该包裹不再跟别的包裹2次混编
	*/
	public function __construct($cart,$express,$province=null) {		
		$this->express = $express;
		foreach ($cart as $key => $value) {
			unset($cart[$key]['memberID']);
		}
		$cart = array_values($cart);//创建索引
		$this->cart = $cart;
		$this->province = trim($province);
		header("Content-type: text/html;charset=utf-8");
	}

	public function getBaoguo(){
		//处理包邮
		$this->setBaoyou(1);
		//处理不包邮
		$this->setBaoyou(0);

 		foreach ($this->baoguoArr as $key => $value) {
 			if ($this->baoguoArr[$key]['totalWuliuWeight']<1) {
				$this->baoguoArr[$key]['totalWuliuWeight']=1;
			}

			$wuliuWeight = ceil($this->baoguoArr[$key]['totalWuliuWeight']*10);
			$this->baoguoArr[$key]['totalWuliuWeight'] = number_format($wuliuWeight/10,1);
			
			$this->baoguoArr[$key]['express'] = $this->express['name'];
			$this->baoguoArr[$key]['expressID'] = $this->express['id'];

        	if($this->baoguoArr[$key]['baoyou']==0){   
        		$this->baoguoArr[$key]['yunfei'] = $this->baoguoArr[$key]['totalWuliuWeight']*$this->express['price'];
        	}else{
        		$this->baoguoArr[$key]['yunfei'] = 0;
        	}
        	$this->baoguoArr[$key]['inprice'] = $this->baoguoArr[$key]['totalWuliuWeight']*$this->express['inprice'];
		}
		return $this->baoguoArr;
	}


	//从包裹中删除商品
	private function deleteBaoguoGoods($baoguo,$goods,$number){
		foreach ($baoguo['goods'] as $key => $value) {
			if ($value['id']==$goods['id']){
				if ($number >= $value['trueNumber']) {					
					array_splice($baoguo['goods'],$key,1);
				}else{
					$baoguo['goods'][$key]['trueNumber'] -= $number;		
				}

				$baoguo['totalNumber'] -= $number;
            	$baoguo['totalWeight'] -= $number*$value['weight'];
            	$baoguo['totalWuliuWeight'] -= $number*$value['wuliuWeight'];
            	$baoguo['totalPrice'] -= $number*$value['price'];
				break;
			}
		}
		return $baoguo;
	}

	//处理包邮类包裹
	private function setBaoyou($isBaoyou){
		$baoguo = [
			'type'=>0, 				//类型
            'totalNumber'=>0, 		//总数量
            'totalWeight'=>0, 		//商品总重量
            'totalWuliuWeight'=>0,	//包装后总重量
            'totalPrice'=>0,  		//商品中金额
            'yunfei'=>0,
            'inprice'=>0,
            'extend'=>0,
            'express'=>$this->express['name'],
            'status'=>1,
            'baoyou'=>$isBaoyou,
            'goods'=>[],
        ];
		foreach ($this->cart as $key => $value) {
			if ($value['baoyou']==$isBaoyou) {
			   	for ($i=0; $i < $value['number']; $i++) {
			   		$goods = $value;
			   		$goods['number'] = 1;
			   		$goods['trueNumber'] = $value['singleNumber'];

			   		$baoguo['totalNumber'] += $goods['singleNumber'];
			   		$baoguo['totalWeight'] += $goods['weight'];
			   		$baoguo['totalWuliuWeight'] += $goods['wuliuWeight'];
			   		$baoguo['totalPrice'] += $goods['price'];                    	                   
                    $this->deleteGoods($value,$goods['singleNumber']);
                }
                $baoguo['type'] = $value['typeID'];
                array_push($baoguo['goods'],$value);
			}
        }
        if($baoguo['totalNumber']>0){
        	array_push($this->baoguoArr,$baoguo);
        }        
	}

	//购物车中减少商品
	private function deleteGoods($item,$number){	
		foreach ($this->cart as $key => $value) {
			if ($value['id']==$item['id']){
				if ($number >= $value['trueNumber']) {					
					array_splice($this->cart,$key,1);
				}else{
					$this->cart[$key]['trueNumber'] -= $number;
				}
				break;
			}
		}
	}
}
?>