<?php
namespace pack;

class EWE {

	private $cart;				//购物车商品
	private $daizhuangnaifen = [];	//袋装奶粉数组
	private $guanzhuangnaifen = [];	//罐装奶粉数组
	private $baoguoArr = [];
	private $province;
	private $extendArea = [];
	private $maxNumber = 10; 	//单个包裹中最多商品个数
	private $maxWeight = 4; 	//单个包裹最大重量(kg)
	private $maxPrice = 200; 	//单个包裹最大金额
	private $express = ['name'=>'EWE'];

	/*
	$cart中的trueNumber是实际单品数量，比如商品A单品数量是3个，如果购物车中有2个，单品数量总数是6，这里的trueNumber不是数据库中单个商品的trueNumber！！！
	包裹的status属性如果是1就是该包裹不再跟别的包裹2次混编
	*/
	public function __construct($cart,$express,$province=null) {	
		$this->express = $express;		
		$this->cart = $cart;
		$this->province = trim($province);
		header("Content-type: text/html;charset=utf-8");
	}

	public function getBaoguo(){
		//处理包邮
		$this->setBaoyou();

		foreach ($this->cart as $key => $value) {
			unset($this->cart[$key]['memberID']);

			if ($value['typeID']==1) {
				array_push($this->daizhuangnaifen, $this->cart[$key]);
				unset($this->cart[$key]);
			}

			if ($value['typeID']==2) {	
				array_push($this->guanzhuangnaifen, $this->cart[$key]);
				unset($this->cart[$key]);
			}
		}
		$this->cart = array_values($this->cart);//创建索引

		//处理一个不混
		$this->singleBaoguo();

		//处理奶粉
		if(count($this->guanzhuangnaifen)>0){
			$this->setNaifenBaoguo($this->guanzhuangnaifen);
		}
		if(count($this->daizhuangnaifen)>0){
			$this->setNaifenBaoguo($this->daizhuangnaifen);
		}

		foreach ($this->cart as $key => $value) {
			if ($value['typeID']==3) {
				$this->goodsInsertBaoguo($value);
            }
		}

		/*foreach ($this->cart as $key => $value) {
			if ($value['typeID']==4) {
				$this->goodsInsertBaoguo($value);
            }
		}

		foreach ($this->cart as $key => $value) {
			if ($value['typeID']==5) {
				$this->goodsInsertBaoguo($value);
            }
		}*/

		foreach ($this->cart as $key => $value) {			
			$this->goodsInsertBaoguo($value);           
		}		

		//包裹重量从大到小排序
		$arr = array();
        foreach ($this->baoguoArr as $key => $row ){
            $arr[$key] = $row ['totalWeight'];
        }
        array_multisort($arr, SORT_DESC, $this->baoguoArr);

        $length = count($this->baoguoArr);

		$lastBaoguo = end($this->baoguoArr);
		//最后一个包裹重量小于1公斤，从其他包裹中匀一部分商品，总重够1公斤就可以了

		if ($lastBaoguo['totalWeight'] < 1 && $lastBaoguo['baoyou']==0) {
			for ($i=0; $i < ($length-1); $i++) {
				if($this->baoguoArr[$i]['status']==0){
					$res = $this->moveGoods($this->baoguoArr[$i],$lastBaoguo);
					$lastBaoguo = $res['to'];
					$this->baoguoArr[$length-1] = $res['to'];
					$this->baoguoArr[$i] = $res['from'];
				}				
			}
		}

 		foreach ($this->baoguoArr as $key => $value) {
 			if ($this->baoguoArr[$key]['totalWuliuWeight']<1) {
				$this->baoguoArr[$key]['totalWuliuWeight']=1;
			}

			$wuliuWeight = ceil($this->baoguoArr[$key]['totalWuliuWeight']*10);
			$this->baoguoArr[$key]['totalWuliuWeight'] = number_format($wuliuWeight/10,1);
			
			$this->baoguoArr[$key]['express'] = $this->express['name'];
			$this->baoguoArr[$key]['expressID'] = $this->express['id'];

	        if (in_array($value['type'],[1,2])){//奶粉类走澳邮
	        	$yunfei = $this->getNaifen($value['type'],$value['totalNumber']);        	
	        	if($this->baoguoArr[$key]['baoyou']==0){
	        		$this->baoguoArr[$key]['yunfei'] = $yunfei;
	        	}else{
	        		$this->baoguoArr[$key]['yunfei'] = 0;
	        	}
	        	//$config = tpCache('express');
	        	//$this->baoguoArr[$key]['inprice'] = $this->baoguoArr[$key]['totalWuliuWeight']*$config['inprice1'];
	        }else{
	        	if($this->baoguoArr[$key]['baoyou']==0){
	        		$this->baoguoArr[$key]['yunfei'] = fix_number_precision($this->baoguoArr[$key]['totalWuliuWeight']*$this->express['price'],2);
	        	}else{
	        		$this->baoguoArr[$key]['yunfei'] = 0;
	        	}	        	
	        	//$this->baoguoArr[$key]['inprice'] = $this->baoguoArr[$key]['totalWuliuWeight']*$danjia['inprice'];
	        }
	        
	        if ($this->inExtendArea()) {
	        	$this->baoguoArr[$key]['extend'] = $this->baoguoArr[$key]['totalWuliuWeight']*$danjia['otherPrice'];
	        }
		}
		return $this->baoguoArr;
	}

	//处理奶粉类
	private function setNaifenBaoguo($goods){
		$total = 0;
		foreach ($goods as $key => $value) {
			$total += $value['trueNumber'];
		}
		$six = floor($total/6); //六件装几箱

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

				if ($six>0) {
					$maxNumber = 6;
				}else{
					$maxNumber = 3;
				}
				if($baoguo['totalNumber']==$maxNumber){
					array_push($this->baoguoArr,$baoguo);
					if($maxNumber==6){
						$six--;
					}
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

	private function goodsInsertBaoguo($item){		
		if(count($this->baoguoArr)==0){
			$baoguo = [
				'type'=>0, 				//类型
	            'totalNumber'=>0, 		//总数量
	            'totalWeight'=>0, 		//商品总重量
	            'totalWuliuWeight'=>0,	//包装后总重量
	            'totalPrice'=>0,  		//商品中金额
	            'yunfei'=>0,	  		//运费
	            'extend'=>0,
	            'express'=>$this->express['name'],
	            'status'=>0,
	            'baoyou'=>0,
	            'goods'=>[],
	        ];
	        array_push($this->baoguoArr,$baoguo);
		}

		//$totalNumber = 0;//计算遍历包裹后该商品一共插入了几个
		foreach ($this->baoguoArr as $key => $value) {
			if($value['status']==0){				
				$number = $this->canInsert($value,$item);	
				if ($number) {
					$oldNumber = $item['trueNumber'];
	            	//可以放入包裹中商品的单品总数量
	            	if($number >= $item['trueNumber']){
	            		$number = $item['trueNumber'];
	            	}
	          		if($number>0){
		            	$item['trueNumber'] = $number;
		            	$this->baoguoArr[$key]['totalNumber'] += $number;
		            	$this->baoguoArr[$key]['totalWeight'] += $number*$item['weight'];
		            	$this->baoguoArr[$key]['totalWuliuWeight'] += $number*$item['wuliuWeight'];
		            	$this->baoguoArr[$key]['totalPrice'] += $number*$item['price'];
		            	$this->baoguoArr[$key]['type'] = $item['typeID'];
		                array_push($this->baoguoArr[$key]['goods'],$item);	                
		                $this->deleteGoods($item,$number);
		                $item['trueNumber'] = $oldNumber - $number;
	           		}
	            }
            }
		}		
		if($item['trueNumber']>0){
			$baoguo = [
				'type'=>0, 				//类型
	            'totalNumber'=>0, 		//总数量
	            'totalWeight'=>0, 		//商品总重量
	            'totalWuliuWeight'=>0,	//包装后总重量
	            'totalPrice'=>0,  		//商品中金额
	            'yunfei'=>0,	  		//运费
	            'extend'=>0,
	            'express'=>'',
	            'status'=>0,
	            'baoyou'=>0,
	            'goods'=>[],
	        ];
	        array_push($this->baoguoArr,$baoguo);
	        $this->goodsInsertBaoguo($item);
		}
	}

	//从包裹中移动商品到目标包裹，目标包裹满足1公斤即可
	private function moveGoods($from,$to){
		foreach ($from['goods'] as $key => $value) {
			$maxNumber = ceil((1-$to['totalWeight'])/$value['weight']);//最多几个就凑够1公斤了	
			$number = $this->canInsert($to,$value);
			if ($number>0 && $maxNumber>0) {
            	$number = $number>$value['trueNumber'] ? $value['trueNumber'] : $number;
            	$number = $number>$maxNumber ? $maxNumber : $number;  
            	$to['totalNumber'] += $number;
            	$to['totalWeight'] += $number*$value['weight'];
            	$to['totalWuliuWeight'] += $number*$value['wuliuWeight'];
            	$to['totalPrice'] += $number*$value['price'];
            	$to['type'] = $value['typeID'];
            	$value['number'] = $number;
            	$value['trueNumber'] = $number;
                array_push($to['goods'],$value);	                
                $from = $this->deleteBaoguoGoods($from,$value,$number);
                if ($to['totalWeight']>=1) {
                	break;
                }
            }
		}
		return ['from'=>$from,'to'=>$to];
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

	//处理一个不混包裹
	private function singleBaoguo(){
		foreach ($this->cart as $key => $value) {
			if ($value['typeID']==7) {
			   	for ($i=0; $i < $value['number']; $i++) {		
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
                        'baoyou'=>0,
                        'goods'=>array($goods),
                    ];
                    array_push($this->baoguoArr,$baoguo);
                    $this->deleteGoods($value,$goods['singleNumber']);
                }
			}
        }
	}	

	//判断当前商品是否能放入包裹
	private function canInsert($baoguo,$item){

		$typeNumberArr = $this->getNumberWeight($baoguo,$item);
		
		$thisMaxNumber = $typeNumberArr['maxNumber'];

		//总数不能超过包裹商品数量
		if ($baoguo['totalNumber']>=$thisMaxNumber) {			
			return false;
		}		

		if(!$this->canHybrid($baoguo,$item)){
			return false;
		}

		//当前待处理的商品包裹类型
		$type = $this->getBaoguoType($item);

		//本类型商品还能放几个
		$itemNumber = $this->getTypeNumber($baoguo,$item);

		$tNum = $thisMaxNumber - $itemNumber['typeNumber'];
		if ($tNum < 1) {
			return false;
		}

		//单品允许放几个
		$sNum = $typeNumberArr['sameNumber'] - $itemNumber['sameNumber'];
		if ($sNum < 1) {
			return false;
		}

		if ($tNum < $sNum) { //得到可以放进包裹的数量
			$number = $tNum;
		}else{
			$number = $sNum;
		}

		//总数不能超过包裹商品数量
		if (($baoguo['totalNumber'] + $number > $thisMaxNumber)) {
			$number = $thisMaxNumber - $baoguo['totalNumber'];
		}

		
		//是否超过总重量，在不超过总重量的情况下最多可以放几个商品
		$weightNumber = $this->getMaxNumber($typeNumberArr['weight'],$baoguo['totalWeight'],$item['weight']);

		$number = $number > $weightNumber ? $weightNumber : $number;

		//是否超过总金额，在不超过总金额的情况下最多可以放几个商品
		$priceNumber = $this->getMaxNumber($this->maxPrice,$baoguo['totalPrice'],$item['price']);

		$number = $number > $priceNumber ? $priceNumber : $number;
	
		return $number;
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

	private function getNaifen($goodsType,$number){
		switch ($number) {
			case 1:
				return 7;
				break;			
			case 2:
				return 13;
				break;
			case 3:
				return 18;
				break;
			case 6:
				return 30;
				break;
		}
	}
}
?>