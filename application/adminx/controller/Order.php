<?php
namespace app\adminx\controller;
use think\Cache;

class Order extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			if($this->admin['administrator']==0){
				$map['cityID'] = $this->admin['cityID'];
			}
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
            if($this->admin['administrator']==0){
                $city = db("City")->where('id',$this->admin['cityID'])->select();
            }else{
                $city = db("City")->select();
            }            
            $this->assign('city', $city);

	    	return view();
    	}
	}

	public function nopay() {
		if (request()->isPost()) {
			$map['status'] = 0;
			$map['cancel'] = 0;
			if($this->admin['administrator']==0){
				$map['cityID'] = $this->admin['cityID'];
			}
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
    		if($this->admin['administrator']==0){
                $city = db("City")->where('id',$this->admin['cityID'])->select();
            }else{
                $city = db("City")->select();
            }            
            $this->assign('city', $city);
    		$this->assign('url',url('order/nopay'));
	    	return view('normal');
    	}
	}

	public function peing() {
		if (request()->isPost()) {
			$map['status'] = 1;
			$map['cancel'] = 0;
			if($this->admin['administrator']==0){
				$map['cityID'] = $this->admin['cityID'];
			}
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
    		if($this->admin['administrator']==0){
                $city = db("City")->where('id',$this->admin['cityID'])->select();
            }else{
                $city = db("City")->select();
            }            
            $this->assign('city', $city);
    		$this->assign('url',url('order/peing'));
	    	return view('normal');
    	}
	}

	public function fahuo() {
		if (request()->isPost()) {
			$map['status'] = array('in',[2,3]);
			$map['cancel'] = 0;
			if($this->admin['administrator']==0){
				$map['cityID'] = $this->admin['cityID'];
			}
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
    		if($this->admin['administrator']==0){
                $city = db("City")->where('id',$this->admin['cityID'])->select();
            }else{
                $city = db("City")->select();
            }            
            $this->assign('city', $city);
    		$this->assign('url',url('order/fahuo'));
	    	return view('normal');
    	}
	}

	public function close() {
		if (request()->isPost()) {
			$map['status'] = 99;
			$map['cancel'] = 1;
			if($this->admin['administrator']==0){
				$map['cityID'] = $this->admin['cityID'];
			}
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
    		if($this->admin['administrator']==0){
                $city = db("City")->where('id',$this->admin['cityID'])->select();
            }else{
                $city = db("City")->select();
            }            
            $this->assign('city', $city);
    		$this->assign('url',url('order/close'));
	    	return view('normal');
    	}
	}

	public function check() {
		if (request()->isPost()) {
			$map['status'] = array('neq',99);
			$map['cancel'] = 1;
			if($this->admin['administrator']==0){
				$map['cityID'] = $this->admin['cityID'];
			}
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
    		if($this->admin['administrator']==0){
                $city = db("City")->where('id',$this->admin['cityID'])->select();
            }else{
                $city = db("City")->select();
            }            
            $this->assign('city', $city);
    		$this->assign('url',url('order/check'));
	    	return view('normal');
    	}
	}

	#添加
	public function email() {
		$id = input('get.id');
		if ($id=='' || !is_numeric($id)) {
			$this->error("参数错误");
		}
		$list = db('OrderEmail')->where('orderID',$id)->find();
		$this->assign('list', $list);
		return view();
	}

    //订单详情
	public function info(){
		if(request()->isPost()){
            $orderID = input('param.id'); 
	        $remark = input('post.remark');
	        $total = input('post.total/f');

	        $list = db('Order')->where(array('id'=>$orderID))->find();
	        if(!$list){
	        	$this->error("订单不存在");
	        }

	        $order['remark'] = $remark;
	        if ($total>0) {
	        	$order['total'] = $total;
	        }
            db('Order')->where(array('id'=>$orderID))->update($order);
            $this->success('操作成功');
        }else{
			$id = input("param.id");
			if (!isset ($id)) {
				$this->error('参数错误');
			}
			$obj = db('Order');
			$map['id'] = $id;
			$list = $obj->where($map)->find();
			if (!$list) {
				$this->error('信息不存在');
			}else{
				$goods = db("OrderCart")->where("orderID",$list['id'])->select(); 
            	$this->assign('goods',$goods);
		
                $baoguo = db('OrderBaoguo')->where('orderID',$list['id'])->select();
                foreach ($baoguo as $k => $val) {
                    $baoguo[$k]['goods'] = db('OrderDetail')->where(array('baoguoID'=>$val['id']))->select();
                    if($val['image']){
                        $baoguo[$k]['image'] = explode(",", $val['image']);
                    }
                    if($val['eimg']){
                        $baoguo[$k]['eimg'] = explode(",", $val['eimg']);
                    }
                }

                $list['shopName'] = db("Shop")->where('id',$list['shopID'])->value("name");
	            $this->assign('baoguo',$baoguo);
	            $this->assign('list',$list);
	            return view();
			}
		}
	}

	public function change(){
		$id = explode(",",input('post.id'));
		$status = input("param.status");
		if (count($id)==0) {
			$this->error('请选择要取消的数据');
		}else{
            $map['id'] = array('in',$id);
			db("Order")->where($map)->setField('status',$status);
			$this->success("操作成功");
		}
	}

	public function refund(){
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要取消的数据');
		}else{
            $map['id'] = array('in',$id);
			db("Order")->where($map)->setField('refund',1);
			$this->success("操作成功");
		}
	}

	public function cancel(){
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要取消的数据');
		}else{
            foreach ($id as $key => $value) {
            	$list = db("Order")->where('id',$value)->find();
            	if($list['status']>0){
            		$detail = db("OrderCart")->where("orderID",$value)->select();
	                foreach ($detail as $k => $val) {       
	                    db('Goods')->where('id',$value['goodsID'])->setInc("stock",$value['trueNumber']);                
	                }
            	}            	
            	db("Order")->where('id',$value)->setField(['status'=>99,'cancel'=>1]);
            	db('OrderDetail')->where('orderID',$value)->setField('cancel',1);
            	db('OrderBaoguo')->where('orderID',$value)->setField('cancel',1);
            }
			$this->success("操作成功");
		}
	}

	public function wuliu(){
		if (request()->isPost()) {
			$id = input("param.id");
			$data['express'] = input("param.express");
			$data['kdNo'] = input("param.kdNo");
			$data['eimg'] = input("param.eimg");
			$data['image'] = input("param.image");
			$orderID = input("param.orderID");

			if ($id=='') {
	            $this->error('参数错误');
	        }
	        if ($data['kdNo']=='') {
	            $this->error('请输入运单号');
	        }else{
	        	$data['kdNo'] = str_replace("，",",",$data['kdNo']);
	        }
	        $map['id'] = $id;
	        if ($data['image']) {
	        	$data['flag'] = 1;
	        }else{
	        	$data['flag'] = 0;
	        }
	        $res = db('OrderBaoguo')->where($map)->update($data);
	        if ($res) {
	        	if ($data['flag']==1) {
	        		$where['orderID'] = $orderID;
		        	$where['flag'] = 0;
		        	$count = db("OrderBaoguo")->where($where)->count();
		        	if ($count==0) {
		        		unset($map);
		        		$map['id'] = $orderID;
		        		$map['status'] = array('in',[1,2]);
		        		db("Order")->where($map)->setField("status",3);
		        	}
	        	}
	        }
	        $this->success("操作成功");
		}else{
			$id = input("param.id");
			$map['id'] = $id;
			$list = db("OrderBaoguo")->where($map)->find();
			if (!$list) {
				$this->error("信息不存在");
			}
			if ($list['eimg']) {
            	$list['eimg'] = explode(",", $list['eimg']);
            }
			if ($list['image']) {
            	$list['image'] = explode(",", $list['image']);
            }
			$this->assign('list',$list);
			return view();
		}
	}

	public function image(){
        //获取要下载的文件名
        $filename = '.'.input('param.img');
        //设置头信息
        header('Content-Disposition:attachment;filename=' . basename($filename));
        header('Content-Length:' . filesize($filename));
        //读取文件并写入到输出缓冲
        readfile($filename);
    }

	
	#删除
	public function del() {
		$id = explode(",",input('post.id'));
		if (count($id)==0) {
			$this->error('请选择要删除的数据');
		}else{
			if(model('Order')->del($id)){
				$map['orderID'] = array('in',$id);
				db("OrderBaoguo")->where($map)->delete();
				db("OrderCart")->where($map)->delete();
				db("OrderDetail")->where($map)->delete();
				$this->success("操作成功");
			}else{
				$this->error('操作失败');
			}
		}
	}

	//创建运单
	public function create(){
		if (request()->isPost()) {
			$id = input("post.id");
			if ($id=="" || !is_numeric($id)) {
				$this->error("参数错误");
			}
			$map['id']=$id;
			$list = db("OrderBaoguo")->where($map)->find();
			if (!$list) {
				$this->error("信息不存在");
			}
			$res = $this->createSingleOrder($list);
			if ($res['code']==1) {
				db("OrderBaoguo")->where($map)->setField('kdNo',$res['msg']);
				$this->success("操作成功，运单号：".$res['msg']);
			}else{
				$this->error($res['msg']);
			}
		}
	}

	public function uploadPhoto(){
		if (request()->isPost()){
			$id = input("post.id");
			if ($id=="" || !is_numeric($id)) {
				$this->error("参数错误");
			}

			$map['id']=$id;
			$list = db("OrderBaoguo")->where($map)->find();
			if (!$list) {
				$this->error("信息不存在");
			}
			if ($list['kdNo']=='') {
				$this->error("请先生成运单");
			}
			$order = db('Order')->where('id',$list['orderID'])->find();

			if ($order['front']=='' || $order['back']=='') {
				$this->error("请先完善身份证信息");
			}

			$config = config("aue");
			$token = $this->getAueToken();
			$data = [
				'OrderIds'=>[$list['kdNo']],
				'ReceiverName'=>$order['name'],
				'ReceiverPhone'=>$order['mobile'],
				'PhotoID'=>$order['sn'],
				'PhotoFront'=>base64EncodeImage('.'.$order['front']),
				'PhotoRear'=>base64EncodeImage('.'.$order['back'])
			];

			$url = 'http://aueapi.auexpress.com/api/PhotoIdUpload';
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization: Bearer '.$token));
			$result = curl_exec($ch);
			$result = json_decode($result,true);
			if ($result['Code']==0 && $result['ReturnResult']=='Success') {
				db("OrderBaoguo")->where($map)->setField('snStatus',1);
				$this->success("上传成功");
			}else{
				$this->error("操作失败");
			}
		}
	}

	public function mprint(){
		$ids = input('get.ids');
		$ids = explode(",",$ids);

		$map['eimg'] = array('neq','');
		$map['id'] = array('in',$ids);
		db("OrderBaoguo")->where($map)->setField('print',1);

		$list = db("OrderBaoguo")->where($map)->select();
		$this->assign('list',$list);

		/*unset($map);
		$map['id'] = array('in',$ids);
		$map['eimg'] = array('neq','');
		$map['type'] = array('in',[1,2,3]);
		$map['sign'] = array('eq','');
		db("OrderBaoguo")->where($map)->update(['flag'=>1,'updateTime'=>time()]);


		foreach ($list as $key => $value) {
			unset($where);
			$where['orderID'] = $value['orderID'];
        	$where['print'] = 0;
        	$printNumber = db("OrderBaoguo")->where($where)->count();//未打印总数

        	unset($where);
			$where['orderID'] = $value['orderID'];
        	$where['flag'] = 0;
        	$flagNumber = db("OrderBaoguo")->where($where)->count();//未发货总数


        	unset($map);
    		$map['id'] = $value['orderID'];
    		$map['payStatus'] = array('in',[2,3]);
        	if ($flagNumber==0 && $printNumber==0) {
        		db("Order")->where($map)->setField("payStatus",4);
        	}elseif($printNumber==0){
	        	db("Order")->where($map)->setField("payStatus",3);
        	}
		}*/
		return view();
	}

	public function export(){
    	$cityID = input('get.cityID');
    	$shopID = input('get.shopID');
    	$payType = input('get.payType');
    	$status = input('get.status');
        $createDate = input('get.date');
    	$ids = input('get.ids');

    	if ($shopID!='') {
            $map['shopID'] = $shopID;
        }

        if($this->admin['administrator']==0){
            $map['cityID'] = $this->admin['cityID'];
        }else{
            if ($cityID!='') {
                $map['cityID'] = $cityID;
            }
        }

        if ($ids!='') {
            $map['id'] = array('in',$ids);;
        }        
        if ($createDate!='') {
            $date = explode(" - ", $createDate);
            $startDate = $date[0];
            $endDate = $date[1];
            $map['createTime'] = array('between',array(strtotime($startDate),strtotime($endDate)+86399));
        }
        if($status!=''){
        	$map['status'] = $status;
        }
        if($payType!=''){
        	$map['payType'] = $payType;
        }
        
        $list = db('Order')->where($map)->order('id desc')->select();
        foreach ($list as $key => $value) {    
        	$goods = db("OrderCart")->where("orderID",$value['id'])->select();
			$content = '';
			foreach ($goods as $k => $val) {
                $goodsName = $val['name'];
                if ($val['spec']!='') {
                    $goodsName .= '('.$val['spec'].')'; 
                }								
				if ($k==0) {
					$content .= $goodsName.'*'.$val['number'];
				}else{
					$content .= ";".$goodsName.'*'.$val['number'];
				}				
			}
			$list[$key]['goods'] = $content;
			$lirun = $value['total'] - $value['wallet'] - $value['inprice'] - $value['payment']; 
			$list[$key]['lirun'] = sprintf("%.2f",$lirun);
        }

        $objPHPExcel = new \PHPExcel();    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '订单号')            
            ->setCellValue('C1', '会员ID')
            ->setCellValue('D1', '推荐人ID')
            ->setCellValue('E1', '收件人')
            ->setCellValue('F1', '电话')
            ->setCellValue('G1', '地址')
            ->setCellValue('H1', '商品')
            ->setCellValue('I1', '发件人')
            ->setCellValue('J1', '订单金额')
            ->setCellValue('K1', '订单状态')
            ->setCellValue('L1', '支付方式')
            ->setCellValue('M1', '积分抵扣')
            ->setCellValue('N1', '实付金额')
            ->setCellValue('O1', '成本')
            ->setCellValue('P1', '运费')
            ->setCellValue('Q1', '优惠')
            ->setCellValue('R1', '利润');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $v['id'])                
                ->setCellValue('B'.$num, ' '.$v['order_no'])                
                ->setCellValue('C'.$num, $v['memberID'])
                ->setCellValue('D'.$num, $v['tjID'])                 
                ->setCellValue('E'.$num, $v['name'])                 
                ->setCellValue('F'.$num, $v['tel'])                 
                ->setCellValue('G'.$num, $v['province'].'/'.$v['city'].'/'.$v['county'].'/'.$v['addressDetail'])                 
                ->setCellValue('H'.$num, $v['goods'])
                ->setCellValue('I'.$num, $v['sender'].'/'.$v['senderTel'])
                ->setCellValue('J'.$num, $v['total'])
                ->setCellValue('K'.$num, $v['status'])
                ->setCellValue('L'.$num, $v['payType'])
                ->setCellValue('M'.$num, $v['wallet'])
                ->setCellValue('N'.$num, $v['money'])
                ->setCellValue('O'.$num, $v['inprice'])
                ->setCellValue('P'.$num, $v['payment'])
                ->setCellValue('Q'.$num, $v['discount'])
                ->setCellValue('R'.$num, $v['lirun']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('订单');
        $objPHPExcel->setActiveSheetIndex(0);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="订单'.date("Y-m-d",time()).'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); 
    }
}
?>