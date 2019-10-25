<?php
namespace pack;

class PX {

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
		$this->setBaoyou();

		//将所有商品数量从大往小排列
		$arr = array();
        foreach ($this->cart as $key => $row ){
            $arr[$key] = $row ['trueNumber'];
        }
        array_multisort($arr, SORT_DESC, $this->cart);

		$this->setHongjiuBaoguo($this->cart);

		//非包邮包裹数量
		$num = 0;
		$goodsNumber = 0;
		foreach ($this->baoguoArr as $key => $value) {
			if($value['baoyou']==0){
				$num++;
				$goodsNumber += $value['totalNumber'];
			}
		}

		$aveMoney = (ceil($goodsNumber/12)*15)/$num; //每个包裹的平均运费
 		foreach ($this->baoguoArr as $key => $value) {
 			if ($this->baoguoArr[$key]['totalWuliuWeight']<1) {
				$this->baoguoArr[$key]['totalWuliuWeight']=1;
			}

			$wuliuWeight = ceil($this->baoguoArr[$key]['totalWuliuWeight']*10);
			$this->baoguoArr[$key]['totalWuliuWeight'] = number_format($wuliuWeight/10,1);
			
			$this->baoguoArr[$key]['express'] = $this->express['name'];
			$this->baoguoArr[$key]['expressID'] = $this->express['id'];

        	if($this->baoguoArr[$key]['baoyou']==0){
        		$ext = 0;
        		foreach ($value['goods'] as $k => $val) {
        			$ext += $val['extra']*$val['trueNumber'];
        		}
        		$this->baoguoArr[$key]['yunfei'] = $aveMoney+$ext;
        	}else{
        		$this->baoguoArr[$key]['yunfei'] = 0;
        	}

        	/*$this->baoguoArr[$key]['inprice'] = $this->baoguoArr[$key]['totalWuliuWeight']*$danjia['inprice'];
		        
	        if ($this->inExtendArea()) {
	        	$this->baoguoArr[$key]['extend'] = $this->baoguoArr[$key]['totalWuliuWeight']*$danjia['otherPrice'];
	        }*/
		}
		return $this->baoguoArr;
	}

	//处理奶粉类
	private function setHongjiuBaoguo($goods){	
		$baoguo = [
			'type'=>0, 				//类型
            'totalNumber'=>0, 		//总数量
            'totalWeight'=>0, 		//商品总重量
            'totalWuliuWeight'=>0,	//包装后总重量
            'totalPrice'=>0,  		//商品中金额
            'yunfei'=>0,	  		//运费
            'extend'=>0,
            'express'=>'',
            'status'=>1,
            'baoyou'=>0,
            'goods'=>[],
        ];
		foreach ($goods as $key => $value) {
			$item = $value;
			for($i=0;$i<$value['trueNumber'];$i++){
				$baoguo['type'] = $item['typeID'];
				$baoguo['totalNumber']++;
				$baoguo['totalWeight'] += $item['weight'];
				$baoguo['totalPrice'] += $item['price'];
				$baoguo['totalWuliuWeight'] += $item['wuliuWeight'];					
				if($this->checkGoodsInBaoguo($baoguo,$value)){
					$baoguo = $this->setBaoguoGoodsNumber($baoguo,$value['goodsID'],1);
				}else{
					$item['trueNumber'] = 1;
					array_push($baoguo['goods'],$item);	
				}

				if($baoguo['totalNumber']==6){
					array_push($this->baoguoArr,$baoguo);			
					$baoguo = [
						'type'=>0, 				//类型
			            'totalNumber'=>0, 		//总数量
			            'totalWeight'=>0, 		//商品总重量
			            'totalWuliuWeight'=>0,	//包装后总重量
			            'totalPrice'=>0,  		//商品中金额
			            'yunfei'=>0,	  		//运费
			            'extend'=>0,
			            'express'=>$this->express['name'],
			            'status'=>1,
			            'baoyou'=>0,
			            'goods'=>[],
			        ];
				}
			}
		}
		if($baoguo['totalNumber']>0){
			array_push($this->baoguoArr,$baoguo);
		}
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
	private function setBaoyou(){
		foreach ($this->cart as $key => $value) {
			if ($value['baoyou']==1) {
			   	for ($i=0; $i < $value['number']; $i++) {
			   		//$value['number'] = 1;
			   		//$value['trueNumber'] = $value['singleNumber'];
			   		$goods = $value;
			   		$goods['number'] = 1;
			   		$goods['trueNumber'] = $value['singleNumber'];
                    $baoguo = [
                        'type'=>$goods['typeID'],
                        'totalNumber'=>$goods['singleNumber'],
                        'totalWeight'=>$goods['weight'],
                        'totalWuliuWeight'=>$goods['wuliuWeight'],
                        'totalPrice'=>$goods['price'],
                        'yunfei'=>0,
                        'inprice'=>0,
                        'extend'=>0,
                        'express'=>$this->express['name'],
                        'status'=>1,
                        'baoyou'=>1,
                        'goods'=>array($goods),
                    ];
                    array_push($this->baoguoArr,$baoguo);
                    $this->deleteGoods($value,$goods['singleNumber']);
                }
			}
        }
	}

	//返回当前包裹+新商品，组合后包裹商品最大数和最大重量
	private function getNumberWeight($baoguo,$item){
		$ids = [];
		foreach ($baoguo['goods'] as $key => $value) {
			if(!in_array($value['typeID'],$ids)){
				array_push($ids,$value['typeID']);
			}
		}
		$typeNumber = count($ids);
		if($typeNumber>=3){
			if($item['typeID']==6){
				return ['weight'=>4,'maxNumber'=>6,'sameNumber'=>2];
			}else{
				return ['weight'=>4,'maxNumber'=>8,'sameNumber'=>99];
			}			
		}
		if($typeNumber==2){
			if(in_array(3,$ids) && in_array(4,$ids) && in_array($item['typeID'],$ids)){
				return ['weight'=>4,'maxNumber'=>10,'sameNumber'=>99];
			}elseif($item['typeID']==6){
				return ['weight'=>4,'maxNumber'=>6,'sameNumber'=>2];
			}else{
				return ['weight'=>4,'maxNumber'=>8,'sameNumber'=>99];
			}	
		}
		if($typeNumber==1){
			if($item['typeID']==3 && in_array(3,$ids)){
				return ['weight'=>4.5,'maxNumber'=>15,'sameNumber'=>99];
			}
			if($item['typeID']==6 && in_array(6,$ids)){
				return ['weight'=>4,'maxNumber'=>3,'sameNumber'=>3];
			}
			return ['weight'=>4,'maxNumber'=>10,'sameNumber'=>99];
		}
		if($typeNumber==0){
			if($item['typeID']==3){
				return ['weight'=>4.5,'maxNumber'=>15,'sameNumber'=>99];
			}
			if($item['typeID']==6){
				return ['weight'=>4,'maxNumber'=>3,'sameNumber'=>3];
			}
			return ['weight'=>4,'maxNumber'=>10,'sameNumber'=>99];
		}
	}

	
	//判断与当前包裹中的商品能否混寄
	private function canHybrid($baoguo,$item){
		foreach ($baoguo['goods'] as $key => $value) {
			$arr = $this->getBaoguoType($value);
			if (!in_array($item['typeID'],$arr['can']) && $item['typeID']!=$value['typeID']) {
				return false;
				break;
			}
		}
		return true;
	}

	//获取当前商品包裹类型
	private function getBaoguoType($item){
		foreach (config('EWE_BAOGUO_TYPE') as $key => $value) {
			if ($item['typeID'] == $value['id']) {
				return $value;
				break;
			}
		}
	}

	//商品是否在包裹中
	private function checkGoodsInBaoguo($baoguo,$goods){
		foreach ($baoguo['goods'] as $key => $value) {
			if($value['goodsID'] == $goods['goodsID']){
				return true;
				break;
			}
		}
		return false;
	}

	//包裹中指定商品数量增加
	private function setBaoguoGoodsNumber($baoguo,$goodsID,$number){
		foreach ($baoguo['goods'] as $key => $value) {
			if($value['goodsID'] == $goodsID){
				$baoguo['goods'][$key]['trueNumber'] += $number;
			}
		}
		return $baoguo;
	}

	//根据重量，金额当前包裹可以放几个商品
	private function getMaxNumber($max,$baoguo,$item){
		if ($item <= 0) {
			return 999;
		}else{
			$number = ($max - $baoguo) / $item;
			return floor($number);
		}
	}

	//包裹中当前类型商品有几个
	private function getTypeNumber($baoguo,$item){
		$typeNumber = 0; //同类型数量
		$sameNumber = 0; //单品数量
		foreach ($baoguo['goods'] as $key => $value) {
			if ($value['typeID']==$item['typeID']) {
				$typeNumber += $value['trueNumber'];
			}
			if ($value['goodsID']==$item['goodsID']) {
				$sameNumber += $value['trueNumber'];
			}
		}
		return ['sameNumber'=>$sameNumber,'typeNumber'=>$typeNumber];
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

	//判断是否在偏远地区
	private function inExtendArea(){
		if (in_array($this->province,$this->extendArea)) {
			return true;
		}else{
			return false;
		}
	}
}
?>