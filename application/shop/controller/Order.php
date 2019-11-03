<?php
namespace app\shop\controller;
use think\Cache;

class Order extends Admin {

	#列表
	public function index() {
		if (request()->isPost()) {
			$map['shopID'] = $this->admin['id'];
            $map['cancel'] = 0;
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
	    	return view();
    	}
	}

	public function nopay() {
		if (request()->isPost()) {
			$map['status'] = 0;
            $map['cancel'] = 0;
			$map['shopID'] = $this->admin['id'];
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
    		$this->assign('url',url('order/nopay'));
	    	return view('normal');
    	}
	}

	public function peing() {
		if (request()->isPost()) {
			$map['status'] = 1;
            $map['cancel'] = 0;
			$map['shopID'] = $this->admin['id'];
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
    		$this->assign('peing',1);
    		$this->assign('url',url('order/peing'));
	    	return view('normal');
    	}
	}

	public function fahuo() {
		if (request()->isPost()) {
            $map['cancel'] = 0;
			$map['status'] = array('in',[2,3]);
			$map['shopID'] = $this->admin['id'];
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{
    		$this->assign('url',url('order/fahuo'));
	    	return view('normal');
    	}
	}

	public function close() {
		if (request()->isPost()) {
			$map['status'] = 99;
			$map['shopID'] = $this->admin['id'];
			$result = model('Order')->getList($map);			
			echo json_encode($result);
    	}else{            
    		$this->assign('url',url('order/close'));
	    	return view('normal');
    	}
	}

    public function cancel() {
        if (request()->isPost()) {
            $map['status'] = array('neq',99);
            $map['cancel'] = 1;
            $map['shopID'] = $this->admin['id'];
            $result = model('Order')->getList($map);            
            echo json_encode($result);
        }else{
            $this->assign('cancel',1);
            $this->assign('url',url('order/cancel'));
            return view('normal');
        }
    }

    //订单详情
	public function info(){
		if(request()->isPost()){
            $orderID = input('param.id'); 
	        $remark = input('post.remark');
	        $total = input('post.total/f');

	        $map['shopID'] = $this->admin['id'];
	        $map['id'] = $orderID;
	        $list = db('Order')->where($map)->find();
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
	            $this->assign('baoguo',$baoguo);
	            $this->assign('list',$list);
	            return view();
			}
		}
	}

	public function send(){
		$id = explode(",",input('post.id'));
		$status = input("param.status");
		if (count($id)==0) {
			$this->error('请选择要取消的数据');
		}else{
            $map['id'] = array('in',$id);
            $map['shopID'] = $this->admin['id'];
			db("Order")->where($map)->setField('send',1);
			$this->success("操作成功");
		}
	}

	public function doCancel(){
        if (request()->isPost()) {
            $id = input("post.id");
            $remark = input("post.remark");
            if($remark==''){
                $this->error('请输入取消理由');
            }
            $map['id'] = $id;
            $map['status'] = 1;
            $map['cancel'] = 0;
            $data['shopID'] = $this->admin['id'];
            db("Order")->where('id',$id)->update(['cancel'=>1,'remark'=>$remark]);
            db('OrderDetail')->where('orderID',$id)->setField('cancel',1);
            db('OrderBaoguo')->where('orderID',$id)->setField('cancel',1);
            $this->success("操作成功");
        }else{
            $id = input("param.id");
            $map['id'] = $id;
            $map['status'] = 1;
            $map['cancel'] = 0;
            $data['shopID'] = $this->admin['id'];
            $list = db("Order")->where($map)->find();
            if (!$list) {
                $this->error('信息不存在');
            }
            $this->assign('list',$list);
            return view();
        }
        die;
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

	public function image(){
        //获取要下载的文件名
        $filename = '.'.input('param.img');
        //设置头信息
        header('Content-Disposition:attachment;filename=' . basename($filename));
        header('Content-Length:' . filesize($filename));
        //读取文件并写入到输出缓冲
        readfile($filename);
    }

	public function export(){
        $createDate = input('get.date');
        $send = input('get.send');
        $ids = input('get.ids');

        $map['shopID'] = $this->admin['id'];
   		if ($send!='') {
            $map['send'] = $send;
        } 
        if ($ids!='') {
            $map['id'] = array('in',$ids);
        }        
        if ($createDate!='') {
            $date = explode(" - ", $createDate);
            $startDate = $date[0];
            $endDate = $date[1];
            $map['createTime'] = array('between',array(strtotime($startDate),strtotime($endDate)+86399));
        }
        $map['quhuoType'] = 0;
        $list = db('Order')->where($map)->order('id desc')->select();
        foreach ($list as $key => $value) {
            $goods = db("OrderCart")->where("orderID",$value['id'])->select();
            $content = '';
            foreach ($goods as $k => $val) {            
                $goodsName = $val['name'];             
                if ($k==0) {
                    $content .= $goodsName.'*'.$val['number'];
                }else{
                    $content .= ";".$goodsName.'*'.$val['number'];
                }               
            }
            $list[$key]['goods'] = $content;
        }

        $objPHPExcel = new \PHPExcel();    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', '编号')
            ->setCellValue('B1', '订单号')
            ->setCellValue('C1', '商品')
            ->setCellValue('D1', '姓名')
            ->setCellValue('E1', '电话')
            ->setCellValue('F1', '地址');
        foreach($list as $k => $v){
            $num=$k+2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$num, $v['id'])                
                ->setCellValue('B'.$num, ' '.$v['order_no'])                
                ->setCellValue('C'.$num, $v['goods'])
                ->setCellValue('D'.$num, $v['name'])                 
                ->setCellValue('E'.$num, $v['tel'])
                ->setCellValue('F'.$num, $v['province'].'/'.$v['city'].'/'.$v['county'].'/'.$v['addressDetail']);
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